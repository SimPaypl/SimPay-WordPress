<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Tables;

use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\PaywallModeInterface;
use WP_List_Table;

class PaywallPostsTable extends WP_List_Table
{

    public function __construct(private readonly PaywallModeInterface $paywallModeService)
    {
        parent::__construct([]);
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = [$columns, $hidden, $sortable];

        $this->items = $this->get_table_data();
    }

    public function get_columns(): array
    {
        return [
            'post_title' => 'Post title',
            'user_nicename' => 'User name',
            'payment_date' => 'Date of payment',
        ];
    }

    private function get_table_data()
    {
        return $this->paywallModeService->getPostsByUser();
    }

    public function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    public function column_post_title(array $item): string
    {
        return sprintf('<a href="%s">%s</a>', get_edit_post_link($item), $item['post_title']);
    }

    public function column_user_nicename(array $item): string
    {
        return sprintf('<a href="%s">%s</a>', get_edit_user_link($item['user_id']), $item['user_nicename']);
    }
}
