<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-2-25
 * Time: 下午3:10
 */
class UserRelationModel extends RelationModel{
    protected  $tableName ='user';
    protected $_link=array(
        'greens'=>array(
            'mapping_type'=>MANY_TO_MANY,
            'foreign_key'=>'u_id',
            'relation_foreign_key'=>'g_id',
            'relation_table'=>'mx_user_greens'
        )
    );
}