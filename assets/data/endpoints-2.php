<?php
    $baseurl='https://www.tyre24.com/nl/nl/user/login/page/L25sL25sL3VzZXIv';
    /* download cacert from curl.haxx.se and edit path */
    $cacert=realpath( 'c:/wwwroot/cacert.pem' );
    /* temporary cookie file */
    $cookiestore=tempnam( sys_get_temp_dir(), '_cookiejar_' );

    $zipfile='result.zip';

    /* login details */
    $params=array(
        'userid'    =>  '123abc',
        'password'  =>  'xyz999'
    );

    $headers=array();


    /* stage 1: get the page, store cookies - mmm-cookies */
    $curl=curl_init( $baseurl );
    /* set some base options used for all requests */
    $baseoptions=array(
        CURLOPT_SSL_VERIFYPEER  =>  false,
        CURLOPT_SSL_VERIFYHOST  =>  2,
        CURLOPT_CAINFO          =>  $cacert,
        CURLOPT_AUTOREFERER     =>  true,
        CURLOPT_FOLLOWLOCATION  =>  true,
        CURLOPT_FORBID_REUSE    =>  false,
        CURLOPT_FAILONERROR     =>  false,
        CURLOPT_HEADER          =>  false,
        CURLOPT_RETURNTRANSFER  =>  true,
        CURLOPT_CONNECTTIMEOUT  =>  15,
        CURLOPT_TIMEOUT         =>  90,
        CURLOPT_USERAGENT       =>  $_SERVER['HTTP_USER_AGENT'],
        CURLINFO_HEADER_OUT     =>  false,
        CURLOPT_VERBOSE         =>  true
    );

    /* specific options for initial request where you need to capture cookies */
    $options=array_merge( $baseoptions, array(
        CURLOPT_COOKIEFILE      =>  $cookiestore,
        CURLOPT_COOKIEJAR       =>  $cookiestore    
    ));

    /* set the options */
    curl_setopt_array( $curl, $options );
        $result=curl_exec( $curl );
        $info=(object)curl_getinfo( $curl );



    if( $info->http_status==200 ){
        /* stage 2: send login parameters via POST */
        $params=http_build_query( $params );

        $fp = fopen( $zipfile, 'w+');

        $headers[]='Content-Length: '.strlen( $params );

        $options=array_merge( $baseoptions, array(
            CURLOPT_FILE            =>  $fp,
            CURLOPT_COOKIE          =>  $cookiestore,
            CURLOPT_FRESH_CONNECT   =>  false,
            CURLOPT_POST            =>  true,
            CURLOPT_POSTFIELDS      =>  $params,
            CURLOPT_HTTPHEADER      =>  $headers
        ));

        curl_setopt_array( $curl, $options );
            $result=curl_exec( $curl );
            $info=(object)curl_getinfo( $curl );

            if( $info->http_status==200 ){
                /* do other stuff */
            }



        @fclose( $fp );
    } else {
        print_r( $info );
    }


    curl_close( $curl );
    $curl = $result = $info =$baseurl = $params = null;

    echo 'done';
?>