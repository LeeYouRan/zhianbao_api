<?php
/**
 * 默认接口服务类
 *
 * @author: Dm
 */
class Api_Zhianbao_Regulator_Image_Add extends PhalApi_Api {
	
	public function getRules() {
		return array (
				 'Go' => array(
                     'regulatorId' => array('name' => 'regulator_id','type'=>'int','require'=> true,'desc'=> '公司ID'),
                     'imgCatId' => array('name' => 'img_cat_id', 'type' => 'int', 'min' => 0, 'require' => false,  'desc' => '分组ID'),
                     'imgContent' => array('name' => 'img_content', 'type' => 'array', 'min' => 1, 'require' => true,'format' => 'json', 'desc' => '图片内容'),
                     'imgSource' => array('name' => 'img_source', 'type' => 'enum', 'range' => array (0, 1),'default' => 0, 'require' => true, 'desc' => '图片来源 0:本地上传 1:网络图片'),
            ),
		);
 	}
	
  
  /**
     * 添加店铺图片
     * #desc 用于添加店铺图片
     * #return int code 操作码，0表示成功
     * #return int img_id 图片ID
     */
    public function Go() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        //检测监管者是否存在
        $regulatorDomain = new Domain_Zhianbao_Regulator();
        $regulatorInfo = $regulatorDomain->getBaseInfo($this->regulatorId);
        if(! $regulatorInfo){
            $rs['code'] = 118;
            $rs['msg'] = T('Regulator not exists');
            return $rs;
        }

        $data = array(
            'regulator_id' =>$this->regulatorId,
            'img_content' =>$this->imgContent,
            'img_source' =>$this->imgSource,
        );
        if(isset($this->imgCatId)){
            $data['img_cat_id'] = $this->imgCatId;
        }
        $domain = new Domain_Zhianbao_Image();

        try {

            DI ()->notorm->beginTransaction ( 'db_api' );
            $imgId = $domain->addImage($data);
            DI ()->notorm->commit( 'db_api' );

        } catch ( Exception $e ) {

            DI ()->notorm->rollback ( 'db_api' );
            $rs ['code'] = $e->getCode ();
            $rs ['msg'] = $e->getMessage ();
            return $rs;
        }


        $rs['info']['img_id'] = $imgId;

        return $rs;
    }
	
}
