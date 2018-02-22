<?php

namespace SmartReplace\SmartReplace;


use Viron\Request;

class Render Extends \Viron\App\Render{

    public static function html(Request $request) {

        $render = $request->app->getTemplater();
        if(!$render) {
            $request->setStatus(400);
            echo "Bad request";
            return;
        }
        /* @var \Fenom $render */


        static::addExtraForRender($render);

        if($request->error) {
            $request->setStatus($request->error->getCode());
            $error = $request->error;
            while($error->getPrevious()) {
                $error = $error->getPrevious();
            }
            $tpls = [
                strtolower($request->action).'/'.$request->error->getCode().'.tpl',
                'errors/'.$request->error->getCode().'.tpl',
                'errors/default.tpl'
            ];
            foreach($tpls as $tpl) {
                if($render->templateExists($tpl)) {
                    $render->display($tpl, [
                        "request" => $request,
                        "action" => $request->actor,
                        "error" => $error,
                        "content" => null
                    ]);
                    return;
                }
            }
            echo "Error: ".$error->getMessage();
        } else {
            $request->setStatus(200);
            $tpls = [
                strtolower($request->action).'/'.strtolower($request->method).'.tpl',
                strtolower($request->action).'/default.tpl',
                'default.tpl'
            ];

            foreach($tpls as $tpl) {
                if($render->templateExists($tpl)) {
                    $render->display('layout.tpl', [
                        "content_tpl" => $tpl,
                        "request" => $request,
                        "action" => $request->actor,
                        "content" => $request->data,
                        "user" => $request->app->user
                    ]);
                    return;
                }
            }
        }
    }


    public static function addExtraForRender(\Fenom &$render) {

        $render->addFunction('dump', function(array $params) {
            ob_start();
            echo "<pre>";
            var_dump($params['var']);
            echo "</pre>";
            $dump = ob_get_clean();


            echo $dump;
        });
    }
}
