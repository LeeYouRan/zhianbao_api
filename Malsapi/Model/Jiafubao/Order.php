<?php

class Model_Jiafubao_Order extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        return 'jfb_orders';
    }
}
