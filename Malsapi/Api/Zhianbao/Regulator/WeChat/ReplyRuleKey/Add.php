<?php
/**
 * 默认接口服务类
 *
 * @author: Dm
 */
class Api_Zhianbao_Regulator_Wechat_ReplyRuleKey_Add extends PhalApi_Api {
	
	public function getRules() {
		return array (
				 'Go' => array(
                     'replyRuleId' => array('name' => 'reply_rule_id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '回复规则ID'),
                     'keyWord' => array('name' => 'key_words', 'type' => 'string', 'require' => true, 'desc' => '关键词'),
            ),
		);
 	}
	
  
  /**
     * 添加关键词
     * #desc 用于添加自动回复规则的关键词
     * #return int code 操作码，0表示成功
     * #return int reply_rule_id 回复规则ID
     */
    public function Go() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        //查找回复规则
        $domain = new Domain_Zhianbao_ReplyRule();
        $info = $domain->getBaseInfo($this->replyRuleId);

        if (empty($info)) {
            DI()->logger->debug('Reply rule not found', $this->replyRuleId);

            $rs['code'] = 146;
            $rs['msg'] = T('Reply rule not found');
            return $rs;
        }
        //判断关键词是否存在
        $isUsed = $domain->isUsedKeyWord($info['wechat_id'],array($this->keyWord));
        if($isUsed){
            $rs['code'] = 171;
            $rs['msg'] = T('Keyword is already exists');
            return $rs;
        }
        //
        $keyWords = json_decode($info['key_words'],true);
        $keyWords[] = $this->keyWord;
        $data = array(
            'id' => $this->replyRuleId,
            'key_words' => json_encode($keyWords),
        );
        $result = $domain->updateReplyRule($data);
        if(! $result){
            $rs['code'] = 108;
            $rs['msg'] = T('Update failed');
            return $rs;
        }
        $rs['reply_rule_id'] = $this->replyRuleId;
        return $rs;
    }
	
}
