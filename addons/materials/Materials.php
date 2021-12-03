<?php
namespace addons\materials;

use app\common\library\Menu;
use think\Addons;
use think\Request;
use addons\materials\controller\Index;
/**
 * 插件
 */
class Materials extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu=[];
        $config_file= ADDON_PATH ."training" . DS.'config'.DS. "menu.php";
        if (is_file($config_file)) {
            $menu = include $config_file;
        }
        if($menu){
            Menu::create($menu);
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        $info=get_addon_info('training');
        Menu::delete(isset($info['first_menu'])?$info['first_menu']:'training');
        return true;
    }

    /**
     * 插件启用方法
     */
    public function enable()
    {
        $info=get_addon_info('training');
        Menu::enable(isset($info['first_menu'])?$info['first_menu']:'training');
    }

    /**
     * 插件禁用方法
     */
    public function disable()
    {
        $info=get_addon_info('training');
        Menu::disable(isset($info['first_menu'])?$info['first_menu']:'training');
    }

    /**
     * 脚本替换
     */
    public function viewFilter(& $content)
    {
        $request = \think\Request::instance();
        $dispatch = $request->dispatch();
       
        if ($request->module() || !isset($dispatch['method'][0]) || $dispatch['method'][0] != '\think\addons\Route') {
            return;
        }
        $addon = isset($dispatch['var']['addon']) ? $dispatch['var']['addon'] : $request->param('addon');
        if ($addon != 'training') {
            return;
        }
        $style = '';
        $script = '';
        $result = preg_replace_callback("/<(script|style)\s(data\-render=\"(script|style)\")([\s\S]*?)>([\s\S]*?)<\/(script|style)>/i", function ($match) use (&$style, &$script) {
            if (isset($match[1]) && in_array($match[1], ['style', 'script'])) {
                ${$match[1]} .= str_replace($match[2], '', $match[0]);
            }
            return '';
        }, $content);
        $content = preg_replace_callback('/^\s+(\{__STYLE__\}|\{__SCRIPT__\})\s+$/m', function ($matches) use ($style, $script) {
            return $matches[1] == '{__STYLE__}' ? $style : $script;
        }, $result ? $result : $content);
       
    }
    /**
     * 会员中心边栏后
     * @return mixed
     * @throws \Exception
     */
    public function userSidenavAfter()
    {
        $request = Request::instance();
        $controllername = strtolower($request->controller());
        $actionname = strtolower($request->action());
        $index = new Index;
        $countlist = $index->getCount();
        
        $data = [
            'controllername' => $controllername,
            'actionname'     => $actionname,
            'sidenav'        => ['main'],
           // 'count'          => $count
        ];
        
        return $this->fetch('view/hook/user_sidenav_after', $data);
    }
}
