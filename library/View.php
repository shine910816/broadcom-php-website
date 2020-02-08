<?php

/**
 * 视图控制器
 * @author Kinsama
 * @version 2016-12-30
 */
class View
{

    /**
     * Smarty对象类
     */
    private $_smarty;

    /**
     * TPL文件名
     */
    private $_tpl;

    /**
     * 初始化
     */
    public function __construct()
    {
        require_once SRC_PATH . "/driver/Smarty/Smarty.class.php";
        $this->_smarty = new Smarty();
        // 设置模版编译目录
        $this->_smarty->compile_dir = SRC_PATH . "/temp";
        // 设置左边界符
        $this->_smarty->left_delimiter = SMARTY_LT_DELIMITER;
        // 设置右边界符
        $this->_smarty->right_delimiter = SMARTY_RT_DELIMITER;
        return;
    }

    /**
     * 画面显示
     *
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function display($user, $request)
    {
        $ret = $this->_setTemplatePath($request->current_menu, $request->current_act);
        if (Error::isError($ret)) {
            $ret->writeLog();
            $this->errorDisplay($user, $request, $ret);
            return;
        }
        $this->_setDefaultVariable();
        $this->_setNavigation($request);
        $this->_setVariable($user, $request);
        $this->_doDisplay();
        return;
    }

    /**
     * 错误画面显示
     *
     * @param object $user User对象
     * @param object $request Request对象
     * @param object $err_obj 错误对象
     */
    public function errorDisplay($user, $request, $err_obj)
    {
        $this->_setTemplatePath(SYSTEM_ERROR_MENU, SYSTEM_ERROR_ACT);
        $this->_setDefaultVariable();
        $this->_setNavigation($request);
        $this->_setErrorVariable($err_obj);
        $this->_setVariable($user, $request);
        $this->_doDisplay();
        return;
    }

    /**
     * 设置TPL路径及文件名
     *
     * @param string $menu Menu名
     * @param string $act Act名
     */
    private function _setTemplatePath($menu, $act)
    {
        $current_menu = Utility::getFileFormatName($menu);
        $current_act = Utility::getFileFormatName($act);
        $tpl_path = sprintf("%s/menu/%s/tpl/%s%s_%sView.tpl", SRC_PATH, $current_menu, SYSTEM_FILE_HEADER, $current_menu, $current_act);
        if (!is_readable($tpl_path)) {
            $error = Error::getInstance();
            $error->raiseError(ERROR_CODE_NONE_TPL_FILE, basename($tpl_path));
            $error->setPos(__FILE__, __LINE__);
            return $error;
        }
        $this->_smarty->template_dir = dirname($tpl_path);
        $this->_tpl = basename($tpl_path);
        return;
    }

    /**
     * 加载画面默认变量
     */
    private function _setDefaultVariable()
    {
        $this->_smarty->assign("system_page_keyword", SYSTEM_PAGE_KEYWORD);
        $this->_smarty->assign("system_page_description", SYSTEM_PAGE_DESCRIPTION);
        $this->_smarty->assign("page_title", SYSTEM_DEFAULT_TITLE);
        $this->_smarty->assign("max_page", "0");
        $this->_smarty->assign("this_year", date("Y"));
        $this->_smarty->assign("comheader_file", SRC_PATH . "/menu/Common/tpl/BroadcomCommon_ComHeaderView.tpl");
        $this->_smarty->assign("comfooter_file", SRC_PATH . "/menu/Common/tpl/BroadcomCommon_ComFooterView.tpl");
        $this->_smarty->assign("usererror_file", SRC_PATH . "/menu/Common/tpl/BroadcomCommon_ErrorBoxView.tpl");
        $this->_smarty->assign("comnaviga_file", SRC_PATH . "/menu/Common/tpl/BroadcomCommon_ComNavigaView.tpl");
        $this->_smarty->assign("compagina_file", SRC_PATH . "/menu/Common/tpl/BroadcomCommon_ComPaginaView.tpl");
        return;
    }

    /**
     * 加载画面变量
     *
     * @param object $user User对象
     * @param object $request Request对象
     */
    private function _setVariable(User $user, Request $request)
    {
        $current_menu = $request->current_menu;
        $current_act = $request->current_act;
        // 报错
        $user_err_flg = false;
        $user_err_list = array();
        if ($request->isError()) {
            $user_err_flg = true;
            $user_err_list = $request->getError();
        }
        //$subpanel_file = "";
        //if ($request->hasAttribute("subpanel_file")) {
        //    $subpanel_file = $request->getAttribute("subpanel_file");
        //}
        $this->_smarty->assign('current_menu', $current_menu);
        $this->_smarty->assign('current_act', $current_act);
        $this->_smarty->assign('current_page', $request->current_page);
        $this->_smarty->assign('current_level', $request->current_level);
        $this->_smarty->assign('sys_app_host', SYSTEM_APP_HOST);
        $this->_smarty->assign('user_login_flg', $user->isLogin());
        $this->_smarty->assign('user_admin_flg', $user->isAdmin());
        $this->_smarty->assign('user_member_name', $user->getMemberName());
        $this->_smarty->assign('user_member_position', $user->getMemberPositionName());
        $this->_smarty->assign('user_err_flg', $user_err_flg);
        $this->_smarty->assign('user_err_list', $user_err_list);
        $this->_smarty->assign($request->getAttributes());
        $this->_smarty->assign('remote_addr', $user->getRemoteAddr());
        $this->_smarty->assign("left_content", $request->getAttribute("left_content"));
    }

    /**
     * 加载错误画面变量
     *
     * @param object $err_obj 错误对象
     */
    private function _setErrorVariable($err_obj)
    {
        $this->_smarty->assign("err_code", $err_obj->err_code);
        $this->_smarty->assign("err_date", $err_obj->err_date);
        $this->_smarty->assign("page_title", "出错了 - " . SYSTEM_DEFAULT_TITLE);
        return;
    }

    /**
     * 加载导航条内容
     *
     * @param object $request Request对象
     */
    private function _setNavigation(Request $request)
    {
        $nav_list = Config::getNavigation();
        $navigation_flg = "0";
        $disp_nav_list = array();
        $page_title = SYSTEM_DEFAULT_TITLE;
        if (isset($nav_list[$request->current_menu][$request->current_act])) {
            $navigation_flg = "1";
            $disp_nav_list = $nav_list[$request->current_menu][$request->current_act];
            $title_index = count($disp_nav_list);
            if ($title_index > 0) {
                $title_index--;
                if ($disp_nav_list[$title_index] != "") {
                    $page_title = strip_tags($disp_nav_list[$title_index]) . " - " . SYSTEM_DEFAULT_TITLE;
                } else {
                    if ($request->hasAttribute("page_titles")) {
                        $page_title_arr = $request->getAttribute("page_titles");
                        if (!is_array($page_title_arr)) {
                            $page_title_arr = array(
                                $page_title_arr
                            );
                        }
                        $new_title_index = count($page_title_arr) - 1;
                        foreach ($page_title_arr as $page_title_item) {
                            $disp_nav_list[] = $page_title_item;
                        }
                        $page_title = strip_tags($page_title_arr[$new_title_index]) . " - " . SYSTEM_DEFAULT_TITLE;
                    }
                }
            }
        }
        $this->_smarty->assign("page_title", $page_title);
        $this->_smarty->assign("navigation_flg", $navigation_flg);
        $this->_smarty->assign("disp_nav_list", $disp_nav_list);
        return;
    }

    /**
     * 执行画面显示
     */
    private function _doDisplay()
    {
        $this->_smarty->display($this->_tpl);
        return;
    }

    /**
     * 获取本类实例化对象
     *
     * @return object
     */
    public static function getInstance()
    {
        return new View();
    }
}
?>