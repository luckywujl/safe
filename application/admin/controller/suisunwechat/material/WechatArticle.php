<?php

namespace app\admin\controller\suisunwechat\material;

use app\admin\model\suisunwechat\material\WechatArticleDetail;
use app\common\controller\Backend;
use EasyWeChat\Kernel\Messages\Article;
use think\Db;
use think\Exception;
use think\Log;

/**
 * 文章
 *
 * @icon fa fa-circle-o
 */
class WechatArticle extends Backend
{
    
    /**
     * WechatArticle模型对象
     * @var \app\admin\model\suisunwechat\material\WechatArticle
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\suisunwechat\material\WechatArticle;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("list");
            if ($params) {
                Db::startTrans();
                try {
                    $list = json_decode($params,true);
                    if(count($list) == 0){
                        $this->error(__('数据为空', ''));
                    }
                    $this->model->name = $list[0]['title'];
                    $this->model->save();
                    $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
                    $articles = [];
                    foreach ($list as $row){
                        //上传图片消息图片
                        $reg = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';
                        $content = $row['content'];
                        preg_match_all($reg,$content,$imgs);
//                        $res = $wechat->getApp()->material->uploadArticleImage(ROOT_PATH . 'public' . DS . $imgs[1][0]);
//                        $content = str_replace($imgs[1][0],$res['url'],$content);
                        foreach ($imgs[1] as $img){
                            $res = $wechat->getApp()->material->uploadArticleImage(ROOT_PATH . 'public' . DS . $img);
                            $content = str_replace($img,$res['url'],$content);
                        }
                        //上传封面图
                        $res = $wechat->getApp()->material->uploadImage(ROOT_PATH . 'public' . DS . $row['image']);
                        $thumb_media_id = '';
                        if ($res){
                            $thumb_media_id = $res['media_id'];
                        }
                        $article = new Article([
                            'title' => $row['title'],
                            'thumb_media_id' => $thumb_media_id, // 封面图片
                            'author' => $row['author'],
                            'show_cover' => $row['show_cover'],
                            'digest' => $row['digest'],
                            'content' => $content,
                            'source_url' => $row['source_url'],
                        ]);
                        $articles[] = $article;
                        WechatArticleDetail::create([
                            'article_id' => $this->model->id,
                            'title' => $row['title'],
                            'thumb_media_id' => $thumb_media_id, // 封面图片
                            'author' => $row['author'],
                            'image' => $row['image'],
                            'show_cover' => $row['show_cover'],
                            'digest' => $row['digest'],
                            'content' => $row['content'],
                            'source_url' => $row['source_url'],
                        ]);
                    }
                    $res = null;
                    if (count($articles) == 1){
                        foreach ($articles as &$item){
                            $res = $wechat->getApp()->material->uploadArticle($item);
                        }
                    }else{
                        $res = $wechat->getApp()->material->uploadArticle($articles);
                    }
                    if (isset($res['errcode'])){
                        $this->error("保存失败,".$res['errmsg']);
                    }

                    //更新文章media_id
                    if ($res && $res['media_id']){
                        $this->model->media_id = $res['media_id'];
                        $this->model->isUpdate(true)->save();
                    }else{
                        $this->error("保存失败,".json_encode($res));
                    }

                    Db::commit();
                    $this->success("保存成功");
                }catch (Exception $e){
                    $this->error("保存失败,".$e->getMessage());
                    Db::rollback();
                }

            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $rowdata = $this->model->get($ids);
        if (!$rowdata) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("list");
            if ($params) {
                Db::startTrans();
                try {
                    $list = json_decode($params,true);
                    if(count($list) == 0){
                        $this->error(__('数据为空', ''));
                    }
                    $rowdata->name = $list[0]['title'];
                    $rowdata->save();

                    $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
                    $count = 0;
                    foreach ($list as $row){
                        //上传图片消息图片
                        $reg = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';
                        $content = $row['content'];
                        preg_match_all($reg,$content,$imgs);
//                        $res = $wechat->getApp()->material->uploadArticleImage(ROOT_PATH . 'public' . DS . $imgs[1][0]);
//                        $content = str_replace($imgs[1][0],$res['url'],$content);
                        foreach ($imgs[1] as $img){
                            $res = $wechat->getApp()->material->uploadArticleImage(ROOT_PATH . 'public' . DS . $img);
                            $content = str_replace($img,$res['url'],$content);
                        }
                        //上传封面图
                        $res = $wechat->getApp()->material->uploadImage(ROOT_PATH . 'public' . DS . $row['image']);
                        $thumb_media_id = '';
                        if ($res){
                            $thumb_media_id = $res['media_id'];
                        }

                        $article = new Article([
                            'title' => $row['title'],
                            'thumb_media_id' => $thumb_media_id, // 封面图片
                            'author' => $row['author'],
                            'show_cover' => $row['show_cover'],
                            'digest' => $row['digest'],
                            'content' => $content,
                            'source_url' => $row['source_url'],
                        ]);
                        //更新文章详情
                        $data = WechatArticleDetail::get($row['id']);
                        $data->title = $row['title'];
                        $data->thumb_media_id = $row['thumb_media_id'];
                        $data->author = $row['author'];
                        $data->image = $row['image'];
                        $data->show_cover = $row['show_cover'];
                        $data->digest = $row['digest'];
                        $data->content = $row['content'];
                        $data->source_url = $row['source_url'];
                        $data->isUpdate(true)->save();

                        if ($count == 0){
                            $res = $wechat->getApp()->material->updateArticle($rowdata['media_id'],$article);
                        }else{
                            $res = $wechat->getApp()->material->updateArticle($rowdata['media_id'],$article,$count);
                        }
                        $count++;
                    }

                    Db::commit();
                    $this->success("保存成功");
                }catch (Exception $e){
                    $this->error("保存失败,".$e->getMessage());
                    Db::rollback();
                }

            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $details = WechatArticleDetail::all(['article_id' => $rowdata->id]);
        $this->view->assign("row", $rowdata);
        $this->assignconfig("info", $details);
        return $this->view->fetch();
    }
}
