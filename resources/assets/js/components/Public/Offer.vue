<template>
  <div class="wrapper wrapper-style2 wlast">
    <template v-if="!reservationNo && !disabled">
    <article id="contact">
      <header>
        <h2 v-if="step < 4">亲,一分钟搞定</h2>
        <h2 v-else>亲 谢谢您的宝贵时间</h2>
        <span>我们立志于帮您解决问题,打造一站式服务。</span>
        <p>You need We can.</p>
      </header>
      <!-- multistep form -->

      <!-- progressbar -->
      <ul class="list-inline row mininav">
        <li class="active col-xs-3" @click="step = 1">基本信息</li>
        <li :class="[step > 1 ? 'active' : '', 'col-xs-3']" @click="gotostep(2)">物品详情</li>
        <li :class="[step > 2 ? 'active' : '', 'col-xs-3']" @click="gotostep(3)">其他服务</li>
        <li :class="[step > 3 ? 'active' : '', 'col-xs-3']" @click="gotostep(4)">信息确认</li>
      </ul>
      <!-- fieldsets -->
      <fieldset v-show="step == 1" class="row col-xs-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3>基本信息</h3>
            <h3 class="box-title">请尽量详细填写相关信息</h3>
          </div>
          <div class="box-body text-left">
            <div class="row">
              <div class="col-xs-6">
                <div class="cfmdwk">
                  <div class="btspan">您的称呼</div>
                  <input type="text" v-model="offer.s1.name" class="form-control" placeholder="请输入您的称呼" />
                  <div class="btspan">希望日期</div>
                  <input type="text" v-model="offer.s1.hopedate" class="form-control datepicker" placeholder="点击选择日期" readonly>
                </div>
              </div>
              <div class="col-xs-6">
                <div class="cfmdwk">
                  <div class="btspan">联系方式<span class="red">*</span></div>
                  <div class="form-group">
                    <input type="text" v-model="offer.s1.tel" id="tel" class="form-control" placeholder="请输入您的手机号码" />
                  </div>
                  <div class="btspan">希望时间</div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="apm" value="1" v-model="offer.s1.apm">
                      {{ $t('event.morning') }} 
                    </label>
                    <label>
                      <input type="radio" name="apm" value="2" v-model="offer.s1.apm">
                      {{ $t('event.afternoon') }} 
                    </label>
                    <label>
                      <input type="radio" name="apm" value="3" v-model="offer.s1.apm">
                      {{ $t('event.night') }} 
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-6 mobilew100">
                <h4 class="text-center">出发地情况</h4>
                <div class="cfmdwk">
                  <div class="btspan">
                    现住所<span class="red">*</span>
                  </div>
                  <input type="text" v-if="offer.s1.fromadr1" v-model="offer.s1.fromadr1" class="form-control" readonly>
                  <div class="form-group">
                    <input type="text" v-model.lazy="offer.s1.fromadr2" id="fromadr2" class="form-control" placeholder="请补全地址 例）6-1-1 Aマンション306" />
                  </div>
                  <div class="input-group">
                    <select class="form-control typeselect" v-model="offer.s1.fbtype">
                      <option value="0">建筑类型</option>
                      <option value="1">团地</option>
                      <option value="2">マンション</option>
                      <option value="3">高级マンション</option>
                      <option value="4">アパート</option>
                      <option value="6">一戸建</option>
                      <option value="5">办公楼</option>
                    </select>
                    <select class="form-control typeselect" v-model="offer.s1.flc">
                      <option value="0">请选择楼层</option>
                      <option v-for="floor of floors" :value="floor"><div v-if="floor > 9">{{ floor }}+</div><div v-else>{{ floor }}</div></option>
                    </select>
                  </div>
                  <div class="input-group">
                    <div class="radio">
                      有无电梯<span class="red">*</span>
                      <label>
                        <input type="radio" name="fdt" value="1" v-model="offer.s1.fdt">
                        有
                      </label>
                      <label>
                        <input type="radio" name="fdt" value="2" v-model="offer.s1.fdt">
                        无 
                      </label>
                      <label>
                        <input type="radio" name="fdt" value="3" v-model="offer.s1.fdt">
                        电梯夹杂着楼梯 
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xs-6 mobilew100">
                <h4 class="text-center">目的地情况</h4>
                <div class="cfmdwk">
                  <div class="cfmdhr">
                    <div class="btspan">
                      目的地<span class="red">*</span>
                    </div>
                    <div class="form-group">
                      <input type="text" v-model="offer.s1.toadr" id="toadr" class="form-control" placeholder="请输入目的地详细地址" />
                    </div>
                  </div>
                  <div class="input-group">
                    <select class="form-control typeselect" v-model="offer.s1.tbtype">
                      <option value="0">建筑类型</option>
                      <option value="1">团地</option>
                      <option value="2">マンション</option>
                      <option value="3">高级マンション</option>
                      <option value="4">アパート</option>
                      <option value="6">一戸建</option>
                      <option value="5">办公楼</option>
                    </select>
                    <select class="form-control typeselect" v-model="offer.s1.tlc">
                      <option value="0">请选择楼层</option>
                      <option v-for="floor of floors" :value="floor"><div v-if="floor > 9">{{ floor }}+</div><div v-else>{{ floor }}</div></option>
                    </select>
                  </div>
                  <div class="input-group">
                    <div class="radio">
                      有无电梯<span class="red">*</span>
                      <label>
                        <input type="radio" name="tdt" value="1" v-model="offer.s1.tdt">
                        有
                      </label>
                      <label>
                        <input type="radio" name="tdt" value="2" v-model="offer.s1.tdt">
                        无 
                      </label>
                      <label>
                        <input type="radio" name="tdt" value="3" v-model="offer.s1.tdt">
                        电梯夹杂着楼梯 
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="input-group input-group-sm">
              <span class="pull-right">
                <button type="button" class="btn btn-info btn-flat" @click="gotostep(2)">{{ gotonext }}</button>
              </span>
            </div>
            <!-- /input-group -->
          </div>
          <!-- /.box-body -->
        </div>
      </fieldset>
      <fieldset v-if="step == 2" class="row col-xs-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3>お荷物の数量</h3>
            <h5 class="box-title"><span class="red">温馨提示:</span>为了尽快准确无误的给您报价,请您一定认真选择,尽量不要漏选。如果您的物品不在以下选项当中或者您有什么特别需求的话,可以在本页最下端进行描述说明。</h5>
          </div>
          <div class="box-body no-padding">
            <p class="text-center">■家具</p>
            <!-- ▼category_wrap【家具】 -->
            <div class="itemtable">
              <div class="row colorlinep">
                <div class="col-xs-3">
                  <img alt="ベッド" src="/images/furniture23.png">
                  <label>ベッド</label>
                </div>
                <div class="col-xs-9">
                  <div class="col-xs-4 mobilew100">
                    <label>ダブル</label>
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.bed1 > 0 ? offer.s2.bed1-- : offer.s2.bed1 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.bed1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.bed1 < 9 ? offer.s2.bed1++ : offer.s2.bed1 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4 mobilew100">
                    <label>セミダブル</label>
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.bed2 > 0 ? offer.s2.bed2-- : offer.s2.bed2 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.bed2" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.bed2 < 9 ? offer.s2.bed2++ : offer.s2.bed2 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4 mobilew100">
                    <label>シングル</label>
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.bed3 > 0 ? offer.s2.bed3-- : offer.s2.bed3 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.bed3" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.bed3 < 9 ? offer.s2.bed3++ : offer.s2.bed3 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-3">
                  <img alt="ソファー" src="/images/furniture1.png">
                  <label>ソファー</label>
                </div>
                <div class="col-xs-9">
                  <div class="col-xs-4 mobilew100">
                    <label>3人掛け</label>
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.sofa3 > 0 ? offer.s2.sofa3-- : offer.s2.sofa3 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.sofa3" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.sofa3 < 9 ? offer.s2.sofa3++ : offer.s2.sofa3 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4 mobilew100">
                    <label>2人掛け</label>
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.sofa2 > 0 ? offer.s2.sofa2-- : offer.s2.sofa2 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.sofa2" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.sofa2 < 9 ? offer.s2.sofa2++ : offer.s2.sofa2 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4 mobilew100">
                    <label>1人掛け</label>
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.sofa1 > 0 ? offer.s2.sofa1-- : offer.s2.sofa1 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.sofa1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.sofa1 < 9 ? offer.s2.sofa1++ : offer.s2.sofa1 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-left">
                <img alt="収納関連" src="/images/packages_stit01.png">
              </div>
              <div class="row colorlinep">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="食器棚" src="/images/furniture17.png">
                    <label>食器棚</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.cupboard > 0 ? offer.s2.cupboard-- : offer.s2.cupboard = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.cupboard" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.cupboard < 9 ? offer.s2.cupboard++ : offer.s2.cupboard = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="サイドボード・テレビ台" src="/images/furniture7.png">
                    <label>サイドボード・テレビ台</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.stand > 0 ? offer.s2.stand-- : offer.s2.stand = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" max="9" v-model="offer.s2.stand" maxlength="1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.stand < 9 ? offer.s2.stand++ : offer.s2.stand = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="タンス類（大）" src="/images/furniture26.png">
                    <label>タンス類（大）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.drawer1 > 0 ? offer.s2.drawer1-- : offer.s2.drawer1 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.drawer1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.drawer1 < 9 ? offer.s2.drawer1++ : offer.s2.drawer1 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="タンス類（小）" src="/images/furniture27.png">
                    <label>タンス類（小）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.drawer2 > 0 ? offer.s2.drawer2-- : offer.s2.drawer2 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.drawer2" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.drawer2 < 9 ? offer.s2.drawer2++ : offer.s2.drawer2 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row colorlinep">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="衣装ケース" src="/images/other30.png">
                    <label>衣装ケース</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.cases1 > 0 ? offer.s2.cases1-- : offer.s2.cases1 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.cases1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.cases1 < 9 ? offer.s2.cases1++ : offer.s2.cases1 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="本棚" src="/images/furniture35.png">
                    <label>本棚</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.bookshelf > 0 ? offer.s2.bookshelf-- : offer.s2.bookshelf = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.bookshelf" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.bookshelf < 9 ? offer.s2.bookshelf++ : offer.s2.bookshelf = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-left">
                <img alt="テーブル、机類" src="/images/packages_stit02.png">
              </div>
              <div class="row">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="ダイニングテーブル" src="/images/furniture19.png">
                    <label>ダイニングテーブル</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.diningtable > 0 ? offer.s2.diningtable-- : offer.s2.diningtable = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.diningtable" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.diningtable < 9 ? offer.s2.diningtable++ : offer.s2.diningtable = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="リビングテーブル・机" src="https://www.koyoshieki.com/public/images/furniture9.png">
                    <label>リビングテーブル・机<br />※学习桌或电脑桌</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.desk > 0 ? offer.s2.desk-- : offer.s2.desk = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.desk" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.desk < 9 ? offer.s2.desk++ : offer.s2.desk = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row colorlinep">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="こたつ" src="/images/other11.png">
                    <label>こたつ</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.kotatsu > 0 ? offer.s2.kotatsu-- : offer.s2.kotatsu = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.kotatsu" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.kotatsu < 9 ? offer.s2.kotatsu++ : offer.s2.kotatsu = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img alt="ドレッサー" src="/images/furniture29.png">
                    <label>ドレッサー</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.dresser > 0 ? offer.s2.dresser-- : offer.s2.dresser = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.dresser" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.dresser < 9 ? offer.s2.dresser++ : offer.s2.dresser = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- ▲category_wrap【家具】 -->
            <p class="text-center">■家電</p>
            <!-- ▼category_wrap【家電】 -->
            <div class="itemtable">
              <div class="row colorlineg">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics4.png">
                    <label>テレビ（40インチ未満）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.tv1 > 0 ? offer.s2.tv1-- : offer.s2.tv1 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.tv1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.tv1 < 9 ? offer.s2.tv1++ : offer.s2.tv1 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics5.png">
                    <label>テレビ（40インチ以上）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.tv2 > 0 ? offer.s2.tv2-- : offer.s2.tv2 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.tv2" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.tv2 < 9 ? offer.s2.tv2++ : offer.s2.tv2 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics6.png">
                    <label>コンポ、ステレオ</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.stereo > 0 ? offer.s2.stereo-- : offer.s2.stereo = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.stereo" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.stereo < 9 ? offer.s2.stereo++ : offer.s2.stereo = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics8.png" alt="">
                    <label>パソコン</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.pc > 0 ? offer.s2.pc-- : offer.s2.pc = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.pc" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.pc < 9 ? offer.s2.pc++ : offer.s2.pc = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row colorlineg">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics12.png">
                    <label>照明器具</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.lighting > 0 ? offer.s2.lighting-- : offer.s2.lighting = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.lighting" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.lighting < 9 ? offer.s2.lighting++ : offer.s2.lighting = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics14.png">
                    <label>ストーブ、ヒーター</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.stove > 0 ? offer.s2.stove-- : offer.s2.stove = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.stove" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.stove < 9 ? offer.s2.stove++ : offer.s2.stove = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics15.png">
                    <label>冷蔵庫（3ドア以上）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.refrigerator1 > 0 ? offer.s2.refrigerator1-- : offer.s2.refrigerator1 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.refrigerator1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.refrigerator1 < 9 ? offer.s2.refrigerator1++ : offer.s2.refrigerator1 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics16.png" alt="">
                    <label>冷蔵庫（2ドア）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.refrigerator2 > 0 ? offer.s2.refrigerator2-- : offer.s2.refrigerator2 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.refrigerator2" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.refrigerator2 < 9 ? offer.s2.refrigerator2++ : offer.s2.refrigerator2 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row colorlineg">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics18.png" alt="">
                    <label>電子レンジ</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.microwave > 0 ? offer.s2.microwave-- : offer.s2.microwave = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.microwave" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.microwave < 9 ? offer.s2.microwave++ : offer.s2.microwave = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics20.png" alt="">
                    <label>乾燥機</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.drier > 0 ? offer.s2.drier-- : offer.s2.drier = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.drier" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.drier < 9 ? offer.s2.drier++ : offer.s2.drier = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics21.png" alt="">
                    <label>洗濯機（タテ）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.washing1 > 0 ? offer.s2.washing1-- : offer.s2.washing1 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.washing1" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.washing1 < 9 ? offer.s2.washing1++ : offer.s2.washing1 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/electronics22.png" alt="">
                    <label>洗濯機（ドラム）</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.washing2 > 0 ? offer.s2.washing2-- : offer.s2.washing2 = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.washing2" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.washing2 < 9 ? offer.s2.washing2++ : offer.s2.washing2 = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row colorlineg">
                <div class="col-xs-2">
                  <img src="/images/electronics36.png">
                </div>
                <div class="col-xs-5 mw90">
                  <label>エアコン拆除</label>
                  <div class="form-group distable">
                    <input type="text" v-model.number="offer.s2.aircondition1" class="iteminput">
                    <label class="col-sm-2 disflex">台</label>
                  </div>
                  <label>エアコン安装</label>
                  <div class="form-group distable">
                    <input type="text" v-model.number="offer.s2.aircondition2" class="iteminput">
                    <label class="col-sm-2 disflex">台</label>
                  </div>
                </div>
                <div class="col-xs-6" style="margin-top:10px">
                  <label class="w100">
                  空调工事说明：<br />
                  拆除费用 3,000円/台（※标准工事）<br />
                  安装费用 12,000円/台（※标准工事）<br />
                  ※标准工事需同时满足：<br />
                  1,墙体有洞<br />
                  2,室外机放阳台或地面（非狭窄空间）<br />
                  3,配管长度不超过4米（超过部分2000円/米）<br />
                  4,空调电压为110V
                  </label>
                </div>
              </div>
            </div>
            <!-- ▲category_wrap【家電】 -->
            <p class="text-center">■そのほか</p>
            <!-- ▼category_wrap【そのほか】 -->
            <div class="itemtable">
              <div class="row colorliney">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/other28.png">
                    <label>布団</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.futon > 0 ? offer.s2.futon-- : offer.s2.futon = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.futon" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.futon < 9 ? offer.s2.futon++ : offer.s2.futon = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/other10.png">
                    <label>カーペット</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.carpet > 0 ? offer.s2.carpet-- : offer.s2.carpet = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.carpet" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.carpet < 9 ? offer.s2.carpet++ : offer.s2.carpet = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/other32.png">
                    <label>植木・観葉植物</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.plants > 0 ? offer.s2.plants-- : offer.s2.plants = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.plants" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.plants < 9 ? offer.s2.plants++ : offer.s2.plants = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/other31.png">
                    <label>仏壇</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.altar > 0 ? offer.s2.altar-- : offer.s2.altar = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.altar" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.altar < 9 ? offer.s2.altar++ : offer.s2.altar = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row colorliney">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/other33.png">
                    <label>自転車</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.bicycle > 0 ? offer.s2.bicycle-- : offer.s2.bicycle = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.bicycle" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.bicycle < 9 ? offer.s2.bicycle++ : offer.s2.bicycle = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/other34.png">
                    <label>バイク</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.bike > 0 ? offer.s2.bike-- : offer.s2.bike = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.bike" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.bike < 9 ? offer.s2.bike++ : offer.s2.bike = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/other13.png">
                    <label>ピアノ・エレクトーン</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.piano > 0 ? offer.s2.piano-- : offer.s2.piano = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.piano" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.piano < 9 ? offer.s2.piano++ : offer.s2.piano = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 w100">
                  <div class="col-xs-7">
                    <img src="/images/xinglixiangx.png" height="60">
                    <label>スーツケース(行李箱)</label>
                  </div>
                  <div class="col-xs-5">
                    <div class="input-group">
                      <div class="btn-group">
                        <button type="button" @click="offer.s2.suitcase > 0 ? offer.s2.suitcase-- : offer.s2.suitcase = 0" class="btn btn-default"><i class="fa fa-minus"></i></button>
                        <input type="text" v-model="offer.s2.suitcase" class="form-control iteminput" readonly>
                        <button type="button" @click="offer.s2.suitcase < 9 ? offer.s2.suitcase++ : offer.s2.suitcase = 9" class="btn btn-default"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- ▲category_wrap【そのほか】 -->
            <p></p>
            <!-- ▼carton_box01 -->
            
            <div class="row">
              <!-- ▼carton_box01_l -->
              <div class="col-xs-6 mobilew100 no-padding">
                <p class="pack_stit02 size17">■ダンボール 120サイズ<br />（长+宽+高=120厘米,比家用微波炉稍大一些）</p>
                <div class="itemtable">
                  <div class="row colorliney lineheight60">
                    <div class="col-xs-4">
                      <img src="/images/other38.png">
                    </div>
                    <div class="col-xs-4">
                      ダンボール
                    </div>
                    <div class="col-xs-4">
                      <select v-model="offer.s2.cardboard">
                        <option v-for="cardboard of cardboards" :value="cardboard"><div v-if="cardboard > 9">{{ cardboard }}+</div><div v-else>{{ cardboard }}</div></option>
                      </select>
                      個
                    </div>
                  </div>
                  <span>－ 個数の目安 －</span><br />
                  <span>1K&nbsp;…&nbsp;10～15個</span><br />
                  <span>2DK&nbsp;…&nbsp;20～30個</span><br />
                  <span>3DK&nbsp;…&nbsp;50個以上</span>
                  <p class="size12">※およそ、1人あたり10～20個が目安となります。</p>
                </div>
              </div>
              <!-- ▲carton_box01_l -->
              <!-- ▼carton_box01_r -->
              <div class="col-xs-6 mobilew100 no-padding text-center">
                <p>・除上述物品外的其他物品或其他希望。</p>
                <div class="inputHintOverlay" style="position:relative" id="wrapjqiho">
                  <textarea v-model="offer.s2.note" rows="7" cols="47" class="ValiAndSet_txtIraiOther" placeholder="上記以外の家財、そのほかご要望がありましたら、ご記入ください。" name="wpnote"></textarea>
                </div>
              </div>
            </div>
            <div class="input-group input-group-sm">
              <span class="pull-left">
                <button type="button" class="btn btn-info btn-flat" @click="step --">{{ $t('offer.goback') }}</button>
              </span>
              <span class="pull-right">
                <button type="button" class="btn btn-info btn-flat" @click="gotostep(3)">{{ gotonext }}</button>
              </span>
            </div>
          </div>
        </div>
      </fieldset>
      <fieldset v-if="step == 3" class="row col-xs-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h2>其他周边服务</h2>
            <h3 class="box-title">请选择需要的服务</h3>
          </div>
          <div class="box-body no-padding">
            <table class="table">
              <tr>
                <td>空调移装</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.kongtiao" class="onoffswitch-checkbox" id="kongtiao">
                    <label class="onoffswitch-label" for="kongtiao">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
                <td>新家安装网络</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.wangluo" class="onoffswitch-checkbox" id="wangluo">
                    <label class="onoffswitch-label" for="wangluo">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>想买中古品</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.maizg" class="onoffswitch-checkbox" id="maizg">
                    <label class="onoffswitch-label" for="maizg">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
                <td>想卖中古品</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.maihuo" class="onoffswitch-checkbox" id="maihuo">
                    <label class="onoffswitch-label" for="maihuo">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>粗大ゴミ处理</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.chuli" class="onoffswitch-checkbox" id="chuli">
                    <label class="onoffswitch-label" for="chuli">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
                <td>退室清扫</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.qingsao" class="onoffswitch-checkbox" id="qingsao">
                    <label class="onoffswitch-label" for="qingsao">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
              </tr>
              <tr><td colspan="4" class="text-center"><h4>解体清洗</h4></td></tr>
              <tr>
                <td>清洗空调</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.kongtiaojt" class="onoffswitch-checkbox" id="kongtiaojt">
                    <label class="onoffswitch-label" for="kongtiaojt">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
                <td>清洗洗衣机</td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" v-model="offer.s3.xiyiji" class="onoffswitch-checkbox" id="xiyiji">
                    <label class="onoffswitch-label" for="xiyiji">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </td>
              </tr>
            </table>
            <div class="input-group input-group-sm">
              <span class="pull-left">
                <button type="button" class="btn btn-info btn-flat" @click="step --">{{ $t('offer.goback') }}</button>
              </span>
              <span class="pull-right">
                <button type="button" class="btn btn-info btn-flat" @click="step ++">{{ gotonext }}</button>
              </span>
            </div>
          </div>
        </div>
      </fieldset>
      <fieldset v-if="step == 4" class="row col-xs-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="colory">亲 有填错的没? 有漏填的没?</h3>
            <h3 class="box-title">现在还可以修改哦</h3>
          </div>
          <div class="box-body no-padding">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4>■基本信息</h4>
              </div>
              <div class="panel-body">
                <div class="text-left">
                  <div class="col-xs-4 s4block mobilew100">
                    <p v-if="offer.s1.name">您的称呼: {{ offer.s1.name }}</p>
                    <p v-if="offer.s1.tel">联系方式: {{ offer.s1.tel }}</p>
                    <p v-if="offer.s1.hopedate">希望日期: {{ offer.s1.hopedate }}</p>
                    <p v-if="offer.s1.apm">希望时间:<span v-if="offer.s1.apm = 1">上午</span><span v-else-if="offer.s1.apm = 2">下午</span><span v-else>夜间</span></p>
                  </div>
                  <div class="col-xs-4 s4block mobilew100">
                    <p>现住所地址: {{ offer.s1.fromadr1 + offer.s1.fromadr2 }}</p>
                    <p>
                      现住所建筑类型: 
                      <span v-if="offer.s1.fbtype == 1">团地</span>
                      <span v-else-if="offer.s1.fbtype == 2">マンション</span>
                      <span v-else-if="offer.s1.fbtype == 3">高级マンション</span>
                      <span v-else-if="offer.s1.fbtype == 4">アパート</span>
                      <span v-else-if="offer.s1.fbtype == 6">一戸建</span>
                      <span v-else-if="offer.s1.fbtype == 5">办公楼</span>
                      <span v-else>未选择</span>
                    </p>
                    <p>
                      现住所楼层: 
                      <span v-if="offer.s1.flc > 0">{{ offer.s1.flc }}</span>
                      <span v-else>未选择</span>
                    </p>
                    <p>
                      现住所电梯: 
                      <span v-if="offer.s1.fdt == 1">有</span>
                      <span v-else-if="offer.s1.fdt == 2">无</span>
                      <span v-else>电梯夹杂着楼梯</span>
                    </p>
                  </div>
                  <div class="col-xs-4 s4block mobilew100">
                    <p>目的地地址: {{ offer.s1.toadr }}</p>
                    <p>
                      目的地建筑类型: 
                      <span v-if="offer.s1.tbtype == 1">团地</span>
                      <span v-else-if="offer.s1.tbtype == 2">マンション</span>
                      <span v-else-if="offer.s1.tbtype == 3">高级マンション</span>
                      <span v-else-if="offer.s1.tbtype == 4">アパート</span>
                      <span v-else-if="offer.s1.tbtype == 6">一戸建</span>
                      <span v-else-if="offer.s1.tbtype == 5">办公楼</span>
                      <span v-else>未选择</span>
                    </p>
                    <p>
                      目的地楼层: 
                      <span v-if="offer.s1.tlc > 0">{{ offer.s1.tlc }}</span>
                      <span v-else>未选择</span>
                    </p>
                    <p>
                      现住所电梯: 
                      <span v-if="offer.s1.tdt == 1">有</span>
                      <span v-else-if="offer.s1.tdt == 2">无</span>
                      <span v-else>电梯夹杂着楼梯</span>
                    </p>
                  </div>
                  <div class="text-center"><button class="btn btn-info" @click="step = 1">更改</button></div>
                </div>
              </div>
            </div>
            <div class="col-xs-6 no-padding">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>■物品信息</h4>
                </div>
                <div class="panel-body">
                  <div v-if="hasItem('furniture')" class="text-left">
                    <h4><strong>家具</strong></h4>
                    <p v-if="offer.s2.bed1 > 0">ベッド（ダブル）: {{ offer.s2.bed1 }}</p>
                    <p v-if="offer.s2.bed2 > 0">ベッド（セミダブル）: {{ offer.s2.bed2 }}</p>
                    <p v-if="offer.s2.bed3 > 0">ベッド（シングル）: {{ offer.s2.bed3 }}</p>
                    <p v-if="offer.s2.sofa1 > 0">ソファー（3人）: {{ offer.s2.sofa1 }}</p>
                    <p v-if="offer.s2.sofa2 > 0">ソファー（2人）: {{ offer.s2.sofa2 }}</p>
                    <p v-if="offer.s2.sofa3 > 0">ソファー（1人）: {{ offer.s2.sofa3 }}</p>
                    <p v-if="offer.s2.cupboard > 0">食器棚: {{ offer.s2.cupboard }}</p>
                    <p v-if="offer.s2.stand > 0">サイドボード・テレビ台: {{ offer.s2.stand }}</p>
                    <p v-if="offer.s2.drawer1 > 0">タンス類（大）: {{ offer.s2.drawer1 }}</p>
                    <p v-if="offer.s2.drawer2 > 0">タンス類（小）: {{ offer.s2.drawer2 }}</p>
                    <p v-if="offer.s2.cases1 > 0">衣装ケース: {{ offer.s2.cases1 }}</p>
                    <p v-if="offer.s2.bookshelf > 0">本棚: {{ offer.s2.bookshelf }}</p>
                    <p v-if="offer.s2.diningtable > 0">ダイニングテーブル: {{ offer.s2.diningtable }}</p>
                    <p v-if="offer.s2.desk > 0">リビングテーブル・机: {{ offer.s2.desk }}</p>
                    <p v-if="offer.s2.kotatsu > 0">こたつ: {{ offer.s2.kotatsu }}</p>
                    <p v-if="offer.s2.dresser > 0">ドレッサー: {{ offer.s2.dresser }}</p>
                  </div>
                  <h4 v-else><strong>家具: 无</strong></h4>
                  <div v-if="hasItem('electric')" class="text-left">
                    <h4><strong>家电</strong></h4>
                    <p v-if="offer.s2.tv1 > 0">テレビ（40インチ未満）: {{ offer.s2.tv1 }}</p>
                    <p v-if="offer.s2.tv2 > 0">テレビ（40インチ以上）: {{ offer.s2.tv2 }}</p>
                    <p v-if="offer.s2.stereo > 0">コンポ、ステレオ: {{ offer.s2.stereo }}</p>
                    <p v-if="offer.s2.pc > 0">パソコン: {{ offer.s2.pc }}</p>
                    <p v-if="offer.s2.lighting > 0">照明器具: {{ offer.s2.lighting }}</p>
                    <p v-if="offer.s2.stove > 0">ストーブ、ヒーター: {{ offer.s2.stove }}</p>
                    <p v-if="offer.s2.refrigerator1 > 0">冷蔵庫（3ドア以上）: {{ offer.s2.refrigerator1 }}</p>
                    <p v-if="offer.s2.refrigerator2 > 0">冷蔵庫（2ドア）: {{ offer.s2.refrigerator2 }}</p>
                    <p v-if="offer.s2.microwave > 0">電子レンジ: {{ offer.s2.microwave }}</p>
                    <p v-if="offer.s2.drier > 0">乾燥機: {{ offer.s2.drier }}</p>
                    <p v-if="offer.s2.washing1 > 0">洗濯機（タテ）: {{ offer.s2.washing1 }}</p>
                    <p v-if="offer.s2.washing2 > 0">洗濯機（ドラム）: {{ offer.s2.washing2 }}</p>
                    <p v-if="offer.s2.aircondition1 > 0">エアコン拆除: {{ offer.s2.aircondition1 }}</p>
                    <p v-if="offer.s2.aircondition2 > 0">エアコン安装: {{ offer.s2.aircondition2 }}</p>
                  </div>
                  <h4 v-else><strong>家电: 无</strong></h4>
                  <div v-if="hasItem()" class="text-left">
                    <h4><strong>其他</strong></h4>
                    <p v-if="offer.s2.futon > 0">布団: {{ offer.s2.futon }}</p>
                    <p v-if="offer.s2.carpet > 0">カーペット: {{ offer.s2.carpet }}</p>
                    <p v-if="offer.s2.plants > 0">植木・観葉植物: {{ offer.s2.plants }}</p>
                    <p v-if="offer.s2.altar > 0">仏壇: {{ offer.s2.altar }}</p>
                    <p v-if="offer.s2.bicycle > 0">自転車: {{ offer.s2.bicycle }}</p>
                    <p v-if="offer.s2.bike > 0">バイク: {{ offer.s2.bike }}</p>
                    <p v-if="offer.s2.piano > 0">ピアノ・エレクトーン: {{ offer.s2.piano }}</p>
                    <p v-if="offer.s2.suitcase > 0">スーツケース（行李箱）: {{ offer.s2.suitcase }}</p>
                    <p v-if="offer.s2.cardboard > 0">ダンボール: {{ offer.s2.cardboard }}</p>
                    <div v-if="offer.s2.note">
                      <h4>其他物品或其他希望:</h4>
                      {{ offer.s2.note }}
                    </div>
                  </div>
                  <h4 v-else><strong>其他: 无</strong></h4>
                  <div class="text-center"><button class="btn btn-info" @click="step = 2">更改</button></div>
                </div>
              </div>
            </div>
            <div class="col-xs-6 no-padding">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>■其他服务</h4>
                </div>
                <div class="panel-body">
                  <p v-if="offer.s3.kongtiao">空调移装</p>
                  <p v-if="offer.s3.wangluo">新家安装网络</p>
                  <p v-if="offer.s3.maizg">想买中古品</p>
                  <p v-if="offer.s3.maihuo">想卖中古品</p>
                  <p v-if="offer.s3.chuli">粗大ゴミ处理</p>
                  <p v-if="offer.s3.qingsao">退室清扫</p>
                  <p v-if="offer.s3.kongtiaojt">清洗空调</p>
                  <p v-if="offer.s3.xiyiji">清洗洗衣机</p>
                  <div class="text-center"><button class="btn btn-info" @click="step = 3">更改</button></div>
                </div>
              </div>
            </div>
          </div>
          <div class="input-group input-group-sm">
            <span class="pull-right">
              <button type="button" class="btn btn-info btn-flat" @click="submit">{{ $t('global.Submit') }}</button>
            </span>
          </div>
        </div>
      </fieldset>
    </article>
    </template>
    <div v-else-if="!disabled" class="col-xs-12 text-center">
      <h2 class="colory">亲 您辛苦了</h2>
      <span>我们立志于帮您解决问题,打造一站式服务。</span>
      <p>You need We can.</p>
      <h2 class="colory">您的预约编号<br /><span style="color: #4abcc5;font-size: 40px">{{ reservationNo }}</span></h2>
      <h4 style="margin-top: 40px">稍等一下哈,我们的小伙伴马上就会跟您联系.</h4>
      <p style="margin-bottom: 120px">客服直通电话:<a href="tel:080-4188-3379">080-4188-3379</a>  感谢您选择子義駅</p>
    </div>
    <div v-else class="col-xs-12 text-center">
      <br /><br />
      <h2 class="colory">感谢您的关注</h2>
      <p>由于网络估价会存在很多误差</p>
      <p>所以公司目前暂时关闭在线估价系统</p>
      <p>子義合同会社</p>
      <h4 style="margin-top: 40px">您可以跟我们的客服人员联系</h4>
      <p>08041883379 微信ziyijiazheng</p>
      <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </div>
    <loading :loadingShow="loadingShow"></loading>
  </div>
</template>

<script>
import moment from 'moment'
import datetimepicker from 'jquery-datetimepicker'
import Loading from './Loading'

export default {
  mounted() {
    this.setDatePicker()
    if(this.$route.params.zipcode) {
      this.getFromAdr()
    }
    if(this.$route.params.partner){
      this.offer.partner = this.$route.params.partner;
    }
  },
  data() {
    return {
      step: 1,
      gotonext: this.$t('offer.gonext'),
      disabled: true,
      loadingShow: false,
      reservationNo: "",
      floors: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '20', '30', '40', '50'],
      cardboards: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '15', '20', '30', '40', '50', '60', '70', '80', '90'],
      offer: {
        partner: "",
        s1: {
          name: "",
          tel: "",
          hopedate: "",
          apm: "",
          fromadr1: "",
          fromadr2: "",
          flc: "0",
          fbtype: "0",
          fdt: "",
          toadr: "",
          tlc: "0",
          tdt: "",
          tbtype: "0"
        },
        s2: {
          bed1: 0,
          bed2: 0,
          bed3: 0,
          sofa1: 0,
          sofa2: 0,
          sofa3: 0,
          cupboard: 0,
          stand: 0,
          drawer1: 0,
          drawer2: 0,
          cases1: 0,
          bookshelf: 0,
          diningtable: 0,
          desk: 0,
          kotatsu: 0,
          dresser: 0,
          tv1: 0,
          tv2: 0,
          stereo: 0,
          pc: 0,
          lighting: 0,
          stove: 0,
          refrigerator1: 0,
          refrigerator2: 0,
          microwave: 0,
          drier: 0,
          washing1: 0,
          washing2: 0,
          aircondition1: 0,
          aircondition2: 0,
          futon: 0,
          carpet: 0,
          plants: 0,
          altar: 0,
          bicycle: 0,
          bike: 0,
          piano: 0,
          suitcase: 0,
          cardboard: 0,
          note: ""
        },
        s3: {
          kongtiao: false,
          wangluo: false,
          maizg: false,
          maihuo: false,
          chuli: false,
          qingsao: false,
          kongtiaojt: false,
          xiyiji: false
        }
      }
    }
  },
  methods: {
    setDatePicker() {
      const self = this
      $.datetimepicker.setLocale('zh')
      $('.datepicker').datetimepicker({dayOfWeekStart: 1, minDate: new Date(), timepicker:false, format:'Y-m-d'})
      $('.datepicker').on('change', (e) => {
        if($(e.target).val() != ''){
          self.offer.s1.hopedate = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.offer.s1.hopedate = '';
        }
      })
    },
    gotostep(step) {
      if(!this.offer.s1.tel) {
        if(!$('#tel').parent().hasClass('has-error')) {
          $('#tel').parent().addClass('has-error')
        }
      }
      if(!this.offer.s1.fromadr2) {
        if(!$('#fromadr2').parent().hasClass('has-error')) {
          $('#fromadr2').parent().addClass('has-error')
        }
      }
      if(!this.offer.s1.toadr) {
        if(!$('#toadr').parent().hasClass('has-error')) {
          $('#toadr').parent().addClass('has-error')
        }
      }
      if(this.offer.s2.aircondition1 > 0 && this.offer.s2.aircondition2 > 0) {
        this.offer.s3.kongtiao = true
      }
      if(this.offer.s1.tel && this.offer.s1.fromadr2 && this.offer.s1.toadr) {
        var tel = this.offer.s1.tel.replace(/[━.*‐.*―.*－.*\-.*ー.*\-]/gi,'');
        if (!tel.match(/^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/)) {
          alert('请输入正确的电话号码以便我们与您取得联系')
        } else if(!this.offer.s1.fdt && !this.offer.s1.tdt) {
          alert('请选择出发地及目的地电梯情况')
        } else if (!this.offer.s1.fdt) {
          alert('请选择出发地电梯情况')
        } else if (!this.offer.s1.tdt) {
          alert('请选择目的地电梯情况')
        } else {
          this.step = step;
        }
      } else {
        alert('*为必填及必选项，请补全信息后再试')
      }
    },
    getFromAdr() {
      var adr = '';
      axios.get('https://maps.googleapis.com/maps/api/geocode/json', {
        params: {
          key: 'AIzaSyD9KUEEBP2Vzb2Y0qlB1V9ORPAFnADwpx0',
          address: this.$route.params.zipcode,
          language: 'ja'
        }
      })
      .then(function (res) {
        if(res.status == 'OK') {
          var obj = res.results[0].address_components;
          if (obj.length < 5) {
            alert('地址获取失败，请手动输入现住所地址');
            return false;
          }
          if (obj[3]['long_name'].length > 0 && obj[2]['long_name'].length > 0 && obj[1]['long_name'].length > 0 && obj[3]['long_name'] != '日本') {
            console.log(obj[3]['long_name'].length);
            adr = obj[3]['long_name'] + obj[2]['long_name'] + obj[1]['long_name'];
          }
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    hasItem(type) {
      var count = 0;
      if(type == 'furniture') {
        count = this.offer.s2.bed1 + this.offer.s2.bed2 + this.offer.s2.bed3 
            + this.offer.s2.sofa1 + this.offer.s2.sofa2 + this.offer.s2.sofa3 
            + this.offer.s2.cupboard + this.offer.s2.stand + this.offer.s2.drawer1 
            + this.offer.s2.drawer2 + this.offer.s2.cases1 + this.offer.s2.bookshelf 
            + this.offer.s2.diningtable + this.offer.s2.desk + this.offer.s2.kotatsu 
            + this.offer.s2.dresser;
      } else if (type == 'electric') {
        count = this.offer.s2.tv1 + this.offer.s2.tv2 + this.offer.s2.stereo + 
            this.offer.s2.pc + this.offer.s2.lighting + this.offer.s2.stove + 
            this.offer.s2.refrigerator1 + this.offer.s2.refrigerator2 + this.offer.s2.microwave + 
            this.offer.s2.drier + this.offer.s2.washing1 + this.offer.s2.washing2 + 
            this.offer.s2.aircondition1 + this.offer.s2.aircondition2;
      } else {
        // other
        count = this.offer.s2.futon + this.offer.s2.carpet + this.offer.s2.plants + 
            this.offer.s2.altar + this.offer.s2.bicycle + this.offer.s2.bike + 
            this.offer.s2.piano + this.offer.s2.suitcase + this.offer.s2.cardboard;
      }
        
      if(count > 0) {
        return true;
      }
      return false;
    },
    submit() {
      this.loadingShow = true;
      this.$http.post('/offer', this.offer).then(res => {
        var number = String(res.data.id);
        var length = number.length;
        if(number.length < 4){
            for(var i = 0; i < 4 - length; i++) {
                number = "0" + number;
            }
        }
        this.reservationNo = 'Y' + number;
        this.loadingShow = false;
      }).catch(error => {
        alert('数据处理错误，请联系客服');
        this.loadingShow = false;
      });
    }
  },
  watch: {
    aircondition1: function(val) {
      if(isNaN(val)){
        alert('请输入数值');
        this.offer.s2.aircondition1 = 0;
      }
    },
    aircondition2: function(val) {
      if(isNaN(val)){
        alert('请输入数值');
        this.offer.s2.aircondition2 = 0;
      }
    },
    fromadr2: function(val) {
      if(val) {
        $('#fromadr2').parent().removeClass('has-error');
      } else if (!$('#fromadr2').parent().hasClass('has-error')) {
        $('#fromadr2').parent().addClass('has-error');
      }
    },
    toadr: function(val) {
      if(val) {
        $('#toadr').parent().removeClass('has-error');
      } else if (!$('#toadr').parent().hasClass('has-error')) {
        $('#toadr').parent().addClass('has-error');
      }
    },
    tel: function(val) {
      if(val) {
        $('#tel').parent().removeClass('has-error');
      } else if (!$('#tel').parent().hasClass('has-error')) {
        $('#tel').parent().addClass('has-error');
      }
    }
  },
  computed: {
    aircondition1: function() {
      return this.offer.s2.aircondition1;
    },
    aircondition2: function() {
      return this.offer.s2.aircondition2;
    },
    fromadr2: function() {
      return this.offer.s1.fromadr2;
    },
    toadr: function() {
      return this.offer.s1.toadr;
    },
    tel: function() {
      return this.offer.s1.tel;
    }
  },
  components: {
    Loading
  }
}
</script>
<style>
.input-group {
  width: 100%;
}
.row {
  margin: 0;
}
input[type="radio"] {
  margin: 2px 0;
  margin-left: -16px!important;
}
.cfmdwk {
  padding: 0 30px;
  margin: 20px;
}
.red {
  color: #dd4b39;
}
#contact ul {
  margin-bottom: 20px;
}
#contact .fa {
  margin-bottom: 0;
}
.btspan,.input-group {
  padding-top: 10px;
}
.itemtable .input-group {
  padding-top: 2px;
}
.radio {
  margin: 5px 0;
}
.typeselect {
  width: 50%!important;
}
.text-center {
  margin-bottom: 0;
  margin-top: 24px;
}
.s4block {
  margin: 10px 0;
}
.mininav {
  margin-bottom: 30px;
  overflow: hidden;
  counter-reset: step;
}
.mininav li.active:before, .mininav li.active:after {
  background: #046e98;
  color: white;
}
.mininav li:before {
  content: counter(step);
  counter-increment: step;
  width: 20px;
  line-height: 20px;
  display: block;
  font-size: 14px;
  color: #333;
  background: white;
  border-radius: 3px;
  margin: 0 auto 5px auto;
}
.mininav li {
  list-style-type: none;
  text-transform: uppercase;
  position: relative;
}
.box-header h5 {
  line-height: 1.3!important;
}
.itemtable {
  border-radius: 3px;
  border: 1px solid #ccc;
}
.colorlinep {
  background: #ffebdf;
}
.colorlineg {
  background: #e8ffe8;
}
.colorliney {
  background: #fffcde;
}
.iteminput {
  width: 36px!important;
  font-size: 1.2em;
  height: 34px;
  padding: 0 12px;
}
button {
  color: #FFF;
}
label {
  font-weight: 300px;
  font-size: 12px;
}
p, .form-group {
  margin-bottom: 0;
}
.col-xs-5 {
  line-height: 47px;
}
.w100 {
  text-align: left;
}
.mw90 {
  max-width: 120px;
}
.mw90 input {
  float: left;
}
.distable {
  display: table;
}
.disflex {
  display: flex;
}
.lineheight60 {
  line-height: 60px;
}
.onoffswitch {
    position: relative; width: 90px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    display: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 20px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
    font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "是";
    padding-left: 10px;
    background-color: #34A7C1; color: #FFFFFF;
}
.onoffswitch-inner:after {
    content: "否";
    padding-right: 10px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
}
.onoffswitch-switch {
    display: block; width: 18px; margin: 6px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 56px;
    border: 2px solid #999999; border-radius: 20px;
    transition: all 0.3s ease-in 0s; 
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
}
.colory {
  color: #ff7246;
}

@media (max-width: 768px) {
  .cfmdwk {
    padding: 0;
    margin: 5px;
  }
  .w100 {
    width: 100%!important;
    padding: 0;
    padding-left: 10px;
  }
  .mobilew100 {
    width: 100%!important;
  }
  .col-xs-3 img {
    margin-top: 20px;
  }
  .col-xs-7 {
    padding: 0!important;
  }
}
</style>