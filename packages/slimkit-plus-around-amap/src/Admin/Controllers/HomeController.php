<?php

namespace SlimKit\PlusAroundAmap\Admin\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Zhiyi\Plus\Traits\CreateJsonResponseData;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HomeController extends BaseController
{


    public function index(Configuration $config)
    {	
    	$conf = $config->getConfigurationBase();
    	$around = [];

    	$around['sig'] = array_get($conf, 'around-amap.amap-sig');
    	$around['key'] = array_get($conf, 'around-amap.amap-key');
    	$around['tableid'] = array_get($conf, 'around-amap.amap-tableid');
      $around['jssdk'] = array_get($conf, 'around-amap.amap-jssdk');

    	return view('around-amap::index', $around);
    }

    public function save(Request $request, Configuration $config)
    {
   		$aroundAmap = $request->only('amap-sig', 'amap-tableid', 'amap-key', 'amap-jssdk');

   		if(!$aroundAmap['amap-sig']) {
   			return redirect()->back()->withInput()->withErrors('请输入密钥');	
   		}

   		if(!$aroundAmap['amap-tableid']) {
   			return redirect()->back()->withInput()->withErrors('请输入tableid');	
   		}

   		if(!$aroundAmap['amap-key']) {
   			return redirect()->back()->withInput()->withErrors('请输入key');	
   		}

      if(!$aroundAmap['amap-jssdk']) {
        return redirect()->back()->withInput()->withErrors('请输入jssdk地址'); 
      }

    	$conf = $config->set([
    		'around-amap.amap-sig' => $aroundAmap['amap-sig'],
    		'around-amap.amap-tableid' => $aroundAmap['amap-tableid'],
    		'around-amap.amap-key' => $aroundAmap['amap-key'],
        'around-amap.amap-jssdk' => $aroundAmap['amap-jssdk']
    	]);
    	
    	if(array_get($conf, 'around-amap.amap-sig') !== $aroundAmap['amap-sig']) {
    		return redirect()->back()->withInput()->withErrors('密钥保存失败！');
    	}

    	if(array_get($conf, 'around-amap.amap-key') !== $aroundAmap['amap-key']) {
    		return redirect()->back()->withInput()->withErrors('key保存失败！');
    	}

    	if(array_get($conf, 'around-amap.amap-tableid') !== $aroundAmap['amap-tableid']) {
    		return redirect()->back()->withInput()->withErrors('tableid保存失败！');
    	}

      if(array_get($conf, 'around-amap.amap-jssdk') !== $aroundAmap['amap-jssdk']) {
        return redirect()->back()->withInput()->withErrors('jssdk地址保存失败！');
      }

    	return redirect('around-amap/admin')->with('status', '保存成功！ :)');
    }
}
