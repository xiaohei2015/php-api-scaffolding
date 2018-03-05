<?php
namespace common\components\tools;

use Yii;
use common\components\response\ReturnMsg;
/**
 * ParamValidator
 */

class ParamValidator
{

	// 允许的请求方式
	public static $requestMethods = array('get','post','header');
	// 数据类型
	public $dataTypes = array(
		'int',
		'number',
		'string',
		'letter', // all letters
		'json',
        'object',
        'array',
	);

	public static $paramConfigKeys = array(
		'name',          # 参数名称
		'title',		 # 参数应用名称
		'defaultValue',  # 参数如果为空，定义的默认值
		'requestMethod', # 参数请求方式
		'required',      # 是否为必须参数
		'allowEmpty',    # 是否允许为空 1,0
		'type',          # 参数数据类型 defined in $this->dataTypes.
		'length',        # 数据长度
		'pattern',       # 参数需要匹配的正则规则
		'call_back',     # 参数回调函数
		'copyAs',        # 复制参数配置
	);

    public $error = null;


	//// 常用配置生成方法 start

	/**
	 * string类型
	 * @param $param
	 * @param array $extra_config
	 */
	public static function stringParam( $param, $extra_config = array() )
	{
		$config = array(
			'name' => $param,
			'type' => 'string',
			'allowEmpty' => 0,
			'required'   => 1,
			'requestMethod' => 'post',
		);

		empty( $extra_config ) or self::pushExtraConfig($config,$extra_config);
		return $config;
	}


	/**
	 * 数字类型的参数  例如user_id, group_id
	 * @param $name
	 * @param array $extra_config
	 * @return array
	 */
	public static function numberParam( $name, $extra_config = array() )
	{
		$config = array(
			'name' => $name,
			'type' => 'number',
			'required'   => 1,
			'allowEmpty' => 0,
			'requestMethod' => 'post',
		);

		empty( $extra_config ) or self::pushExtraConfig($config,$extra_config);
		return $config;
	}

    public static function objectParam( $name, $extra_config = array() )
    {
        $config = array(
            'name' => $name,
            'type' => 'object',
            'required'   => 1,
            'allowEmpty' => 0,
            'requestMethod' => 'post',
        );

        empty( $extra_config ) or self::pushExtraConfig($config,$extra_config);
        return $config;
    }

    public static function arrayParam( $name, $extra_config = array() )
    {
        $config = array(
            'name' => $name,
            'type' => 'array',
            'required'   => 1,
            'allowEmpty' => 0,
            'requestMethod' => 'post',
        );

        empty( $extra_config ) or self::pushExtraConfig($config,$extra_config);
        return $config;
    }

	/**
	 * json类型
	 * @param $name
	 * @param array $extra_config
	 */
	public static function jsonParam( $name, $extra_config = array() )
	{
		$config = array(
			'name'          => $name,
			'type'          => 'json',
			'required'      => 1,
			'allowEmpty'    => 0,
			'requestMethod' => 'post',
			'call_back'     => 'json_decode',
		);

		empty( $extra_config ) or self::pushExtraConfig($config,$extra_config);
		return $config;
	}


	/**
	 * session_id参数的配置
	 * @param array $extra_config
	 * @return array
	 */
	public static function sessionidParam($extra_config=array())
	{
		$config = array(
			'name' => 'sessionid',
			'type' => 'string',
			'required' => 1,
			'allowEmpty' => 0,
		);

		empty( $extra_config ) or self::pushExtraConfig($config,$extra_config);

		return $config;
	}


	/**
	 * 创建配置 支持批量  要求批量设置的数据类型必须统一
	 * @param array $params
	 * @param string $type
	 * @param $extra_config
	 */
	public static function makeConfig( array $params, $type ,$extra_config = array() )
	{
		if( empty( $params ) ) return array();

		$method = strtolower($type).'Param';
		if( !in_array( $method, get_class_methods('ParamValidator') ) ){
			throw new \Exception( "ParamValidator do not have a method named {$method}" );
		}

		$config = array();
		foreach( $params as $paramName ) {
			$config[] = self::$method( $paramName, $extra_config );
		}

		return $config;
	}


	//// 常用配置生成方法 end


	/**
	 * 推入模板之外的配置
	 * @param $paramconfig
	 * @param array $extra_config
	 */
	private static function pushExtraConfig( &$paramconfig, array $extra_config ){
		if( !empty( $extra_config ) ) {
			foreach ($extra_config as $key => $value) {
				in_array( $key ,self::$paramConfigKeys ) and $paramconfig[$key] = $value;
			}
		}
	}


	/**
	 * 格式化参数配置数据
	 * @param array $paramConfig
	 * @return array
	 * @throws \Exception
	 */
	public function validateConfig( array $paramConfig )
	{
		if( empty( $paramConfig ) ){
			throw new \Exception("Param config should not be an empty array.");
		}

		// validate param configs and reformat config
		$formatedConfig = array();
		foreach( $paramConfig as $config ) {
			if( !isset( $config['name'] ) )
				throw new \Exception("Param config must include the param name key.");
			$formatConfig = array_combine( self::$paramConfigKeys, array_fill(0,count(self::$paramConfigKeys),''));
			$config = array_merge( $formatConfig,$config );

			// bind json_decode call_back for json data
			if( $config['type'] == 'json' ){
				$config['call_back'] = 'json_decode';
			}

			// save formatted config
			$formatedConfig[$config['name']] = $config;
		}
		return $formatedConfig;
	}


	/**
	 * 验证数据格式
	 * @param $value
	 * @param $type
	 * @return mixed
	 * @throws \Exception
	 */
	public function validateDataType( $value, $type )
	{	
		$type = strtolower($type);
		if( !in_array($type,$this->dataTypes) )
			throw new \Exception("Unregistered data type {$type}.");
		$methodName = 'ctype'.ucfirst($type);
		if( !method_exists($this,$methodName) ){
			throw new \Exception("Param validator does not have the {$methodName} method.");
		}else{
			return $this->$methodName($value);
		}
	}

	/**
	 * 数据类型验证 - number
	 * @param $value
	 * @return bool
	 */
	private function ctypeNumber( $value )
	{
		return is_numeric($value);
	}

	/**
	 * @param $value
	 * @return bool
	 */
	private function ctypeLetter( $value )
	{
		return ctype_alpha($value);
	}

	/**
	 * 数据类型验证 - String [可打印字符]
	 * @param $value
	 * @return bool
	 */
	private function ctypeString( $value )
	{
		return is_string($value);
	}

	/**
	 * 数据类型验证 - Json
	 * @param $value
	 * @return bool
	 */
	private function ctypeJson( $value )
	{
		return (bool)json_decode(json_encode($value),true);
	}

	/**
	 * 数据类型验证 - int
	 * @param $value
	 * @return bool
	 */
	private function ctypeInt( $value )
	{
		return is_int($value);
	}

    private function ctypeObject( $value )
    {
        return is_object($value);
    }

    private function ctypeArray( $value )
    {
        return is_array($value);
    }

	/**
	 * 正则验证
	 * @param $value
	 * @param $pattern
	 * @return bool
	 */
	public function ctypeRegx( $value, $pattern )
	{
		return (bool)preg_match($pattern, $value);
	}

	/**
	 * 长度验证
	 * @param $value
	 * @param $length
	 * @return bool
	 */
	public function validateLength( $value, $length )
	{
		return mb_strlen($value,'utf-8') <= $length ? true : false;
	}

    /**
     * 验证Api请求参数 失败将不执行Api控制器
     * @param array $paramConfig
     * @return array
     * @throws \Exception
     */
    public function validateParams( $paramConfig = array() )
    {
        $paramConfig = $this->validateConfig($paramConfig);

        $params = array();
        // validate params only defined in apiConfig
        foreach( $paramConfig as $paramName => $param ) {
            if( isset( $param['copyAs'] ) && $param['copyAs'] ){
                $param = $paramConfig[$param['copyAs']];
                $param['name'] = $paramName;
            }

            // Get param value according the request method.
            $requestMethod = isset( $param['requestMethod'] ) ? strtolower($param['requestMethod']) : '';
            in_array($requestMethod,self::$requestMethods) or $requestMethod = '';
            // Get param value
            $paramVal = self::getParam( $paramName,$requestMethod );
            // 如果允许为空 跳过后续验证
            if( $paramVal == '' && $param['allowEmpty'] ) {
                $params[$paramName] = isset($param['defaultValue'])?$param['defaultValue']:'';
                continue;
            }
            // Validate required
            if( is_null($paramVal) ) {
                // cann't get required param
                if( isset( $param['required'] ) && $param['required'] ) {
                    //$this->outputJson( $this->returnMsg('lessParams', '缺少'.($param['title'] !=''?$param['title']:$param['name']).'参数'));
                    $this->error = ReturnMsg::fail('缺少'.($param['title'] !=''?$param['title']:$param['name']).'参数',$param['name']);
                    return false;
                }else{
                    $paramVal = '';
                }
            }
            // Validate allowEmpty
            if( $paramVal === '' ){
                // Empty value
                if( isset( $param['allowEmpty'] ) && !$param['allowEmpty'] ){
                    //$this->outputJson($this->returnMsg('errorParams', ($param['title'] !=''?$param['title']:$param['name']).'异常或为空'));
                    $this->error = ReturnMsg::fail(($param['title'] !=''?$param['title']:$param['name']).'异常或为空',$param['name']);
                    return false;
                }

                $paramVal = isset( $param['defaultValue'] ) ? $param['defaultValue'] : $paramVal;
            }

            // Validate data type
            if( isset($param['type']) && $param['type'] ){
                $typeStatus = $this->validateDataType($paramVal,$param['type']);
                //$typeStatus or $this->outputJson($this->returnMsg('typeError', ($param['title'] !=''?$param['title']:$param['name']).'类型错误'));
                if(!$typeStatus) {
                    $this->error = ReturnMsg::fail(($param['title'] != '' ? $param['title'] : $param['name']) . '类型错误',$param['name']);
                    return false;
                }
            }

            // length
            if( isset( $param['length'] ) && $param['length'] ){
                $lengthStatus = $this->validateLength($paramVal, $param['length']);
                //$lengthStatus or $this->outputJson($this->returnMsg('lengthError', ($param['title'] !=''?$param['title']:$param['name']).'长度错误,不能超过'.$param['length']));
                if(!$lengthStatus) {
                    $this->error = ReturnMsg::fail(($param['title'] != '' ? $param['title'] : $param['name']) . '长度错误,不能超过' . $param['length'],$param['name']);
                    return false;
                }
            }

            // pattern
            if( isset( $param['pattern'] ) && $param['pattern'] != '' ) {
                $pregStarts = $this->ctypeRegx($paramVal, $param['pattern']);
                //$pregStarts or $this->outputJson($this->returnMsg('patternError', ($param['title'] !=''?$param['title']:$param['name']).'错误'));
                if(!$pregStarts) {
                    $this->error = ReturnMsg::fail(($param['title'] != '' ? $param['title'] : $param['name']) . '错误',$param['name']);
                    return false;
                }
            }


            // Save current param
            $params[$paramName] = $paramVal;
        }
        unset( $validator );

        return $params;
    }

    /**
     * 根据请求方式获取请求参数
     * @param $name
     * @param string $requestMethod
     * @return mixed|null
     * @throws \Exception
     */
    public static function getParam( $name, $requestMethod = '' )
    {
        switch( strtolower($requestMethod) ) {
            case 'get':
                $paramVal = Yii::$app->request->get($name);
                break;
            case 'post':
                $paramVal = Yii::$app->request->post($name);
                break;
            default:
                $paramVal = Yii::$app->request->post($name)?:(Yii::$app->request->get($name)?:'');
                break;
        }

        return $paramVal;
    }

    public function getError()
    {
        return $this->error;
    }
}