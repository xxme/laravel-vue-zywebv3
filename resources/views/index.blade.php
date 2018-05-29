@extends('../layouts.public')

@section('content')
<style>
.flexslider{
  display: none;
}
@media (max-width: 768px) {
  #keyv{
    display: none;
  }
  .flexslider{
    display: block;
  }
}
</style>
<link href="{{ asset('/css/flexslider.css') }}" rel="stylesheet" type="text/css">
<!-- <script type="text/javascript" src="{{ asset('/js/library.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('/js/jquery.bxslider.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery.flexslider-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js') }}/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="{{ asset('/js') }}/retina.js"></script> -->
<!-- <script type="text/javascript" src="{{ asset('/js') }}/jquery.easing.1.3.js"></script> -->
<!-- <script type="text/javascript" src="{{ asset('/js') }}/smoothscroll.js"></script> -->
<!-- <script type="text/javascript" src="{{ asset('/js') }}/jquery-func.js"></script> -->
<!-- <script type="text/javascript" src="{{ asset('/js') }}/jquery-confirm.js"></script> -->
<!-- ==== ABOUT ==== -->
<div id="about" name="about">
  <div class="">
    <div class="row white">
      <div id="keyv">
        <div class="slides-all">
          <div class="slides-wrap" style="background:url({{ asset('/images') }}/banner3.jpg);">
            <div class="bx-wrapper" style="max-width: 2000px;">
              <div class="bx-viewport" style="width: 100%; overflow: hidden; position: relative; height: 430px;">
                <ul class="slides">
                  <li class="bx-clone">
                    <a href="#"><img src="{{ asset('/images') }}/banner3.jpg" width="1000" height="430" alt=""></a>
                  </li>
                  <li class="bx-clone">
                    <a href="#"><img src="{{ asset('/images') }}/b2.jpg" width="1000" height="430" alt=""></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flexslider">
        <ul class="slides">
          <li>
            <img src="{{ asset('/images') }}/b1mb.jpg" />
          </li>
          <li>
            <img src="{{ asset('/images') }}/b2mb.jpg" />
          </li>
        </ul>
      </div>
      <script type="text/javascript">
        $(document).ready(function(){
          if($('#keyv .slides li').length > 1){
            slider = $('#keyv .slides img');
            var keyv_opt = {
              buildPager: function(slideIndex){
                return('<img src="' + $(slider).eq(slideIndex).attr("src") + '">');
              },
              slideWidth: 1000,
              useCss: false,
              auto: true,
              pause: 5000,
              minSlides: 3,
              maxSlides: 3,
              moveSlides: 1,
              slideMargin: 0,
              prevText: 'prev',
              nextText: 'next',
              autoHover: true,
              onSliderLoad: function(){
                $('#keyv .bx-controls-direction a').append('<div></div>');
                $('#keyv .bx-controls-direction a').append('<span></span>');
                $('#keyv .bx-controls-direction a div').css("opacity","0.5");
                $('#keyv .bx-controls-direction a div').mouseover(function(){
                  $(this).stop().fadeTo(150, 0.6);
                  keyv_slider.stopAuto();
                }).mouseout(function(){
                  $(this).stop().fadeTo(150, 0.5);
                  keyv_slider.startAuto();
                });
              },
              onSlideAfter: function($slideElement, oldIndex, newIndex){
                keyv_slider.startAuto();
              }
            }
            $('#keyv .slides').prepend($('#keyv .slides li:last-child'))
            var keyv_slider = $('#keyv .slides').bxSlider(keyv_opt);
          }
          $('.flexslider').flexslider({
              animation: "slide",
              controlNav: false
          });
        });
      </script>

    </div>
    <!-- row -->
  </div>
</div>
<!-- container -->

<!-- ==== SERVICES ==== -->
<div id="services" name="services">
  <div class="container">
    <div class="row">
      <h2 class="centered">主要服务</h2>
      <h3>サービス</h3>
      <hr>
      
      <div class="col-lg-3 callout"> <i class="fa fa-truck fa-3x"></i>
        <h3>日式搬家</h3>
        <p>服务质量 专业水平 严高标准<br />认真细心 准时准点</p>
      </div>
      <div class="col-lg-3 callout"><a href="https://shop.koyoshieki.com" target="_blank"> <i class="fa fa-shopping-cart  fa-3x"></i>
        <h3>中古店舗</h3>
        <p>物美价廉 样式多款 品质保障<br />金牌信誉 售后安心</p>
        </a>
      </div>
      <div class="col-lg-3 callout"> <i class="fa fa-gears fa-3x"></i>
        <h3>空調全般</h3>
        <p>空调维修 空调加氟 空调移机<br />空调清洗 维护保养</p>
      </div>
      <div class="col-lg-3 callout"> <i class="fa fa-bitbucket fa-3x"></i>
        <h3>粗大ゴミ処理</h3>
        <p>一站无忧 闪电预约 准时收取<br />负责细心 安全可靠</p>
      </div>
    </div>
    <!-- row -->
  </div>
</div>
<!-- container -->

<!-- ==== PORTFOLIO ==== -->

<div id="portfolio" name="portfolio">
  <div class="container">
    <div class="row">
      <h2 class="centered">金牌推荐</h2>
      <hr>
      <div class="col-lg-8 col-lg-offset-2 centered">
        <p class="large">异国他乡的美味回忆,舒适安逸的民宿和寮,欢乐无限的旅游攻略,安全可靠的快捷车务,桃李满园的金牌良校,无论您是日本旅游或是日本久居,子義駅一直与您相随.</p>
      </div>
    </div>
    <!-- /row -->
    <div class="container">
      <div class="row">
        <!-- PORTFOLIO IMAGE 1 -->
        <div class="col-lg-3 ">
          <a href="/public/index/tlist.shtml?t=2">
            <div class="grid mask">
              <figure> <img class="img-responsive" src="{{ asset('/images') }}/meishi.jpg" alt="">
                <figcaption><h5>特色美食带您享受饕餮盛宴.</h5></figcaption>
                <!-- /figcaption -->
              </figure>
              <h5>美食推荐</h5>
              <!-- /figure -->
            </div>
          </a>
          <!-- /grid-mask -->
        </div>
        <!-- MODAL SHOW THE PORTFOLIO IMAGE. In this demo, all links point to this modal. You should create
                              a modal for each of your projects. -->
        <!-- PORTFOLIO IMAGE 2 -->
        <div class="col-lg-3">
          <a href="/public/index/tlist.shtml?t=1">
            <div class="grid mask">
              <figure> <img class="img-responsive" src="{{ asset('/images') }}/zhusu.jpg" alt="">
                <figcaption><h5>体验家一样的感觉.</h5></figcaption>
                <!-- /figcaption -->
              </figure>
              <h5>住宿推荐</h5>
              <!-- /figure -->
            </div>
          </a>
          <!-- /grid-mask -->
        </div>

        <!-- PORTFOLIO IMAGE 3 -->
        <div class="col-lg-3">
          <a href="#">
            <div class="grid mask">
              <figure> <img class="img-responsive" src="{{ asset('/images') }}/youwan.jpg" alt="">
                <figcaption><h5>领略不同的风景.</h5></figcaption>
                <!-- /figcaption -->
              </figure>
              <h5>游玩推荐</h5>
              <!-- /figure -->
            </div>
          </a>
          <!-- /grid-mask -->
        </div>
        <div class="col-lg-3 ">
          <div class="grid mask">
            <figure> <img class="img-responsive" src="{{ asset('/images') }}/portfolio/folio04.jpg" alt="">
              <figcaption><h5>备受好评的学校.</h5></figcaption>
                <!-- /figcaption -->
            </figure>
            <h5>金牌学校</h5>
            <!-- /figure -->
          </div>
          <!-- /grid-mask -->
        </div>
      </div>
      <!-- /row -->
    </div>
    <!-- /row -->
  </div>
</div>
<!-- /container -->

<!-- ==== TEAM MEMBERS ==== -->
<div id="team" name="team">
  <div class="container">
    <div class="row centered">
      <h2 class="centered" id="kfry">联系客服</h2>
      <hr>
      <div class="col-lg-3 centered kfwk"> <img class="img img-circle" src="{{ asset('/images') }}/team/guo.jpg" height="120px" width="120px" alt="">
        <h4>担当 小五</h4>
        <a href="javascript:;" class="bind_hover_card" data-toggle="popover" data-placement="bottom" data-trigger="hover"><i class="fa fa-weixin"></i></a>
        <a href="tel:070-3666-0508"><i class="fa fa-phone"></i></a>
      </div>
      <div class="col-lg-3 centered kfwk"> <img class="img img-circle" src="{{ asset('/images') }}/team/xing.jpg" height="120px" width="120px" alt="">
        <h4>担当 小刘</h4>
        <a href="javascript:;" class="bind_hover_card" data-toggle="popover2" data-placement="bottom" data-trigger="hover"><i class="fa fa-weixin"></i></a>
        <a href="tel:080-3539-1314"><i class="fa fa-phone"></i></a>
      </div>
      <div class="col-lg-3 centered kfwk"> <img class="img img-circle" src="{{ asset('/images') }}/team/xue.jpg" height="120px" width="120px" alt="">
        <h4>担当 小薛</h4>
        <a href="javascript:;" class="bind_hover_card" data-toggle="popover3" data-placement="bottom" data-trigger="hover"><i class="fa fa-weixin"></i></a>
        <a href="tel:080-4188-3379"><i class="fa fa-phone"></i></a>
      </div>
      <div class="col-lg-3 centered kfwk"> <img class="img img-circle" src="{{ asset('/images') }}/team/xu.jpg" height="120px" width="120px" alt="">
        <h4>担当 小旭</h4>
        <a href="javascript:;" class="bind_hover_card" data-toggle="popover4" data-placement="bottom" data-trigger="hover"><i class="fa fa-weixin"></i></a>
        <a href="tel:080-4422-9001"><i class="fa fa-phone"></i></a>
      </div>
      <div class="col-lg-8 col-lg-offset-2 centered">
        <p class="large">You need We can.</p>
      </div>
    </div>
  </div>
  <!-- row -->
</div>
<!-- container -->

<!-- ==== CONTACT ==== -->
<div id="contact" name="contact">
  <div class="container">
    <div class="row">
      <h2 class="centered">联系我们</h2>
      <hr>
      <div class="col-md-6 centered"> <i class="fa fa-map-marker fa-2x"></i>
        <p>
          <a href="https://www.google.co.jp/maps/place/9+Chome-28-19+Iriya,+Adachi-ku,+T%C5%8Dky%C5%8D-to+121-0836/@35.8084485,139.752152,17z/data=!3m1!4b1!4m5!3m4!1s0x601893c0488c1ddf:0xf2b5025b43da32b3!8m2!3d35.8084442!4d139.7543407?hl=zh-CN" target="_blank">
            東京都足立区 入谷9-28-19
          </a>
        </p>
      </div>

      <div class="col-md-6"> <i class="fa fa-phone fa-2x"></i>
        <p> <a href="tel:08041883379">080 4188 3379</a></p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12"> <i class="fa fa-tags fa-2x"></i>
        <p> 在线見積もり</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 col-lg-offset-2 centered">
        <!-- <form name="zxmitumori" method="post" class="form" role="form" action="/public/index/order">
          <div class="row">
            <div class="col-xs-6 col-md-6 form-group">
              <input class="form-control" name="zip" placeholder="現住所地址或郵便番号" type="text" required />
              <h5><a href="http://www.post.japanpost.jp/zipcode/" target="_blank">〒郵便番号検索</a></h5>
            </div>
            <div class="col-xs-6 col-md-6 form-group">
              <input class="form-control" name="tel" placeholder="联系电话或微信" type="text" required />
            </div>
          </div> -->
          <div class="row">
            <div class="col-xs-12 col-md-12">
              <a href="{{ route('offer') }}" target="_blank">
                <button class="btn btn btn-lg" type="button">
                  开始
                </button>
              </a>
            </div>
          </div>
        <!-- </form> -->
      </div>
    </div>
    <!-- row -->
  </div>
</div>

<script>
$(function() {
  $("[data-toggle='popover']").popover({
    html : true,
    title: title(),
    delay:{show:500, hide:2000},
    content: function() {
      return content(1);
    }
  });
  $("[data-toggle='popover2']").popover({
    html : true,
    title: title(),
    delay:{show:500, hide:2000},
    content: function() {
      return content(2);
    }
  });
  $("[data-toggle='popover3']").popover({
    html : true,
    title: title(),
    delay:{show:500, hide:2000},
    content: function() {
      return content(3);
    }
  });
  $("[data-toggle='popover4']").popover({
    html : true,
    title: title(),
    delay:{show:500, hide:2000},
    content: function() {
      return content(4);
    }
  });
});

function title() {
  return '扫码加客服微信';
}

function content(kf) {
  if(kf == 1){
    var data = '<p class="qrp">担当 小郭</p><img src="{{ asset('/images') }}/qrguo.jpg" width="120" /><p><a href="tel:070-3666-0508">070-3666-0508</a></p>';
  }else if(kf == 2){
    var data = '<p class="qrp">担当 小刘</p><img src="{{ asset('/images') }}/qrliu.jpg" width="120" /><p><a href="tel:080-3539-1314">080-3539-1314</a></p>';
  }else if(kf == 3){
    var data = '<p class="qrp">担当 小薛</p><img src="{{ asset('/images') }}/qrxue.png" width="120" /><p><a href="tel:080-4188-3379">080-4188-3379</a></p>';
  }else if(kf == 4){
    var data = '<p class="qrp">担当 小旭</p><img src="{{ asset('/images') }}/qrxu.jpg" width="120" /><p><a href="tel:080-4422-9001">080-4422-9001</a></p>';
  }
  return data;
}
</script>
@endsection
