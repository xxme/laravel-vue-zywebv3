<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
	<title>Koyoshieki便利屋</title>
	<meta name="author" content="Wechat:imxing">
	<meta name="keywords" content="搬家,日式搬家,东京搬家,家政服务,中古店铺,二手家电,二手家具,东京条骚市场,空调,空调安装,空调拆装,引越,引越し,引っ越し,引越し 見積もり,引っ越し,見積もり,単身の引越し,家族の引越し,子義引越センター,子義駅,子義駅便民,子義駅便民家政,子義搬家">
	<meta name="description" content="お引越しや引越見積は引越業者の子義駅引越センターにおまかせ。荷物を運んでほしいと思うお客様の心に、精一杯の真心で応えたい。ちゃんとしたお引っ越しをご提供します。">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Bootstrap core CSS -->
	<!-- <link href="/public/css/bootstrap.css" rel="stylesheet"> -->

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="{{ asset('/css/main.css') }}" rel="stylesheet">
	<!-- <link href="/public/css/font-awesome.min.css" rel="stylesheet"> -->
	<link href="{{ asset('/css/animate-custom.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jquery-confirm.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('/css/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/modernizr.custom.js') }}"></script>


	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script data-ad-client="ca-pub-9183236566259705" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>

<body data-spy="scroll" data-offset="0" data-target="#navbar-main">
	<div id="navbar-main">
		<!-- Fixed navbar -->
		<div class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="{{ url('/') }}">
						<img src="{{ asset('/images') }}/logo.jpg" width="180" />
				</div>
				<div>
					<ul class="lxxx navbar-right">
						<li>
							<a href="#">
								<i class="fa fa-phone"></i>
								<a href="tel:08041883379">080-4188-3379</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-weixin"></i> ziyijiazheng</a>
						</li>
					</ul>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="{{ url('/') }}" class="smoothScroll">
								<i class="fa fa-home"></i> 首页
								<span>ホームページ</span>
							</a>
						</li>
						<li>
							<a href="#portfolio" class="smoothScroll">
								<i class="fa fa-angle-right"></i> 子義駅推荐
								<span>Koyoshiekiおすすめ</a>
						</li>
						<li>
							<a href="https://shop.koyoshieki.com" target="_blank" class="smoothScroll">
								<i class="fa fa-angle-right"></i> 中古店铺
								<span>リサイクルショップ</span>
							</a>
						</li>
						<li>
							<a href="{{ route('offer') }}" class="smoothScroll" target="_blank">
								<i class="fa fa-angle-right"></i> 日式搬家
								<span>お引越し</span>
							</a>
						</li>
						<li>
							<a href="#" class="smoothScroll">
								<i class="fa fa-angle-right"></i> 空调全般
								<span>エアコン</span>
							</a>
						</li>
						<li>
							<a href="#" class="smoothScroll">
								<i class="fa fa-angle-right"></i> 粗大不用品处理
								<span>粗大ゴミ処理</span>
							</a>
						</li>
						<li>
							<a href="{{ route('login') }}" class="smoothScroll">
								<i class="fa fa-angle-right"></i> 加入我们
								<span>新規取得</span>
							</a>
						</li>
					</ul>
				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div id="global-navi" style="left: 0px;">
		<nav>
			<ul>
				<li class="home">
					<a href="/">
						<img src="{{ asset('/images') }}/gnavi_home.gif" width="56" height="40" alt="HOME">
					</a>
				</li>
				<li class="shop">
					<dl>
						<dt>
							<a href="#portfolio">
								<span>
									<span>Koyoshiekiおすすめ</span>
									<br>子義駅推荐</span>
							</a>
						</dt>
					</dl>
				</li>
				<li class="estimate">
					<dl>
						<dt>
							<a href="https://shop.koyoshieki.com" target="_blank">
								<span>
									<span>リサイクルショップ</span>
									<br>中古店铺
									<iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe>
								</span>
							</a>
						</dt>
					</dl>
				</li>
				<li class="plan">
					<dl>
						<dt>
							<a href="{{ route('offer') }}" target="_blank">
								<span>
									<span>お引越し</span>
									<br>日式搬家</span>
							</a>
						</dt>
					</dl>
				</li>
				<li class="about">
					<dl>
						<dt>
							<a href="#">
								<span>
									<span>エアコン</span>
									<br>空调全般</span>
							</a>
						</dt>
					</dl>
				</li>
				<li class="faq">
					<dl>
						<dt>
							<a href="#">
								<span>
									<span>粗大ゴミ処理</span>
									<br>粗大不用品处理</span>
							</a>
						</dt>
					</dl>
				</li>
				<li class="cp">
					<dl>
						<dt>
							<a href="{{ route('login') }}">
								<span>
									<span>新規取得</span>
									<br>加入我们</span>
							</a>
						</dt>
					</dl>
				</li>
			</ul>
		</nav>
	</div>

	@yield('content')

	<div id="footerwrap">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<span class="copyright">Copyright &copy; 2014-2018 子義合同会社. 
					<br />网站合作洽谈<i class="fa fa-weixin"></i> imxing</span>
				</div>
				<script>
					(function (i, s, o, g, r, a, m) {
					i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
						(i[r].q = i[r].q || []).push(arguments)
					}, i[r].l = 1 * new Date(); a = s.createElement(o),
						m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
					})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

					ga('create', 'UA-92343511-1', 'auto');
					ga('send', 'pageview');
				</script>
			</div>
		</div>
  </div>
</body>

</html>