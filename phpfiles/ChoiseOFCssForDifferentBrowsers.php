 <?php 
        //This is the first file that will be run on the log in page or on any other pages.
        //The purpos of this file is to know what browser they are using, what vertion and what OS they are running.
        //The reasone why we nead this is because every browser workes a bit different from each other,
        //and if we create a spasific CSS file that work in one way for one browser, it will work 
        //complitly different on other browsers. To fix that issue, we will be uploading a spasific file
        //for a spasific browser, so if you will load a page on any browser, it will look exactly the same 
        //on any browser.
        //For now we are working on Firefox, Chrome and Safari.
        //IF, everything will go well, we will also add an explore and opera :)
        //
    	
    function getBrowser() {

        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        //
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
       
        // Next get the name of the useragent yes seperately and for good reason
        //
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
       
        // finally get the correct version number
        //
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
       
        // see how many we have
        //
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            //
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
       
        // check if we have a number
        //
        if ($version==null || $version=="") {$version="?";}
       
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }

    // now try it
    //
    $ua=getBrowser();
    $yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
            //css will be chosen for a spasific browser. For now we are working only for Firefox, Safary and Chrome
            //
    		if($ua['name'] == "Mozilla Firefox"){
    ?>
    			<link rel="stylesheet" type="text/css" href="../css/myfcss.css">
    <?php 	
			} else if ($ua['name'] == "Apple Safari"){
    ?>   
                <link rel="stylesheet" type="text/css" href="../css/myScss.css">
    <?php
            } else if ($ua['name'] == 'Google Chrome') {
    ?>
            <link rel="stylesheet" type="text/css" href="../css/myCcss.css">
    <?php
            }
    ?>