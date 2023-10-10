<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\FilterInterface;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\PaywallModeInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Exception\SimPayApiInvalidCredentialsException;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceInterface;
use SimPay\SimPayWordpressPlugin\View\ViewManagerFactory;
use SimPay\SimPayWordpressPlugin\View\ViewManagerInterface;

class AddPaywallOnPost implements FilterInterface
{
    private ViewManagerInterface $view;
    private array $simPayOptions;
    private int $postSmsNumber;
    private bool $paywallActive;

    public function __construct(
        private readonly SimPayServiceInterface $simPayService,
        private readonly PaywallModeInterface $paywallModeService,
    ) {
        $this->view = ViewManagerFactory::create();
        $this->simPayOptions = get_option('simpay_options') ?: [];
    }

    public static function getHookName(): string
    {
        return 'the_content';
    }

    public function __invoke($content): string
    {
        if ($this->simPayOptions['simpay_plugin_mode'] !== 'per_post') {
            return $content;
        }

        if ($this->simPayOptions['simpay_plugin_mode'] !== 'per_post') {
            return $content;
        }

        global $wp_query;

        $paywallActiveOption = get_post_meta(get_the_ID(), 'paywall_active', false)[0] ?? false;
        $postSmsNumber = get_post_meta(get_the_ID(), 'paywall_price', false)[0] ?? false;

        $this->paywallActive = (bool) $paywallActiveOption;
        $this->postSmsNumber = (int) $postSmsNumber;

        if ($this->paywallActive !== true) {
            return $content;
        }

        if (current_user_can('editor') || current_user_can('administrator')) {
            return $content;
        }

        if ($this->paywallModeService->hasUserPaymentForPost(get_current_user_id(), get_the_ID())) {
            return $content;
        }

        if (!is_user_logged_in()) {
            return $this->showNotLoggedInAlert();
        }
        return $this->handlePaywallForm($wp_query);


        return $content;
    }

    private function showNotLoggedInAlert(): string
    {
        $this->view->render('public.paywall.access-denied-alert', [
            'showNotLoggedInInfo' => true,
            'registerUrl' => wp_login_url(),
        ]);

        return '';
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    private function handlePaywallForm(mixed $wpQuery): ?string
    {
        if (isset($_POST['sms_code'])) {
            if ($error = $this->validateSmsForm()) {
                $this->renderSimPayPaymentForm(get_the_ID(), $error);
                return '';
            }
            $this->paywallModeService->grantAccessToPost(get_current_user_id(), get_the_ID());

            $this->renderSuccessfulAlert();
            return '';
        }
        $this->renderSimPayPaymentForm(get_the_ID());

        return '';
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    private function validateSmsForm(): ?string
    {
        if (!isset($_POST['sms_code']) || trim($_POST['sms_code']) === '') {
            $errors = __('<strong>Error</strong>: Enter SMS code.', 'simpay-wordpress');
            return $errors;
        }

        if (!$this->simPayService->getSmsCodeValidation($_POST['sms_code'], $this->postSmsNumber)->isValid()) {
            $errors = __('<strong>Error</strong>: Invalid SMS code.', 'simpay-wordpress');
        }

        return $errors ?? null;
    }

    public function renderSimPayPaymentForm(int $postId, string $error = null): void
    {
        $this->view->render('public.paywall.access-denied-alert', ['error' => $error]);

        try {
            $smsNumber = $this->simPayService->getSmsNumber($this->postSmsNumber);
        } catch (SimPayApiInvalidCredentialsException) {
            return;
        }

        $view = ViewManagerFactory::create();
        $view->render('public.paywall.payment-form', [
            'postId' => $postId,
            'smsNumber' => $smsNumber->getNumber(),
            'smsPrice' => $smsNumber->getPriceGross(),
            'smsCode' => $this->simPayService->getSmsCode()->getCode(),
        ]);
    }

    private function renderSuccessfulAlert(): void
    {
        $this->view->render('public.paywall.successful-payment', [
            'permalink' => get_permalink(),
        ]);
    }
}
