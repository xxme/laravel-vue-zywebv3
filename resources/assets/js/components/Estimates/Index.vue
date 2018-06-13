<template>
	<div class="row col-md-12">
		<section class="content-header">
			<h1>
				{{ $t('estimate.pagetitle') }}
				<small>{{ $t('estimate.subtitle') }}</small>
			</h1>
		</section>
		<!-- Main content -->
		<section class="content" v-if="!showformflag">
			<!-- Main row -->
			<sample-nav></sample-nav>
			<div class="box">
				<div class="box-header">
					<label :class="{ checked: type == 1 }">
						<input type="radio" value="1" v-model="type">
						{{ $t('global.all') }}
					</label>
					<label :class="{ checked: type == 2 }">
						<input type="radio" value="2" v-model="type">
						{{ $t('estimate.untreated') }}
					</label>
					<label :class="{ checked: type == 3 }">
						<input type="radio" value="3" v-model="type">
						{{ $t('estimate.my') }}
					</label>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<th style="width: 40px">#</th>
								<th style="width: 50px">{{ $t('global.details') }}</th>
								<th v-if="type == 1"><i class="fa fa-user-o"></i></th>
								<th>{{ $t('estimate.createdat') }}</th>
                <th>{{ $t('estimate.hopedate') }}</th>
                <th style="width: 10px"><i class="fa fa-flag"></i></th>
							</tr>
							<template v-if="estimates.data.length > 0">
								<template v-for="(list, index) in estimates.data">
									<tr>
										<td>{{ list.id }}</td>
										<td>
											<div class="zy-icon">	
												<span class="bigger-140 show-details-btn" @click="showDetail" title="Show Details">
													<i class="fa fa-angle-double-down"></i>
												</span>
											</div>
										</td>
										<td v-if="type == 1"><span v-if="list.user">{{ list.user.name }}</span></td>
										<td>{{ list.created_at }}</td>
                    <td>
                      <span v-if="list.hopedate">{{ formatDateWithWeekname(list.hopedate) }} </span>
                      <span v-else>日期未指定 </span>
                      <template v-if="list.apm">
                        <span v-if="list.apm == 1">{{ $t('event.morning') }}</span>
                        <span v-else-if="list.apm == 2">{{ $t('event.afternoon') }}</span>
                        <span v-else>{{ $t('event.night') }}</span>
                      </template>
                    </td>
                    <td>
                      <span v-if="list.event_id"><i class="fa fa-check-square-o"></i></span>
                    </td>
									</tr>
									<tr class="detail-row">
										<td colspan="6">
                      <div class="col-xs-12 no-padding">
                        <div class="margintb10">
                          <span v-if="list.user"><i class="fa fa-user-o"></i> {{ list.user.name }} </span>
                          <i class="fa fa-phone"></i> <a :href="'tel:'+list.tel">{{ list.tel }}</a><span v-if="list.nickname"> {{ list.nickname }} </span>
                          <span v-if="list.partner"><i class="fa fa-male"></i> {{ list.partner }}</span>
                          <br />
                          <span v-if="list.event_id" @click="editevent(list.event.id)" class="blue"><i class="fa fa-calendar"></i> {{ list.event.event_date}} #{{ list.event.id }}</span>
                          <div class="pull-right" v-if="!list.event_id">
                            <button class="btn btn-xs btn-warning" @click="showNote(list.id)"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-xs btn-primary" @click="createEvent(list.id)"><i class="fa fa-calendar"></i></button>
                          </div>
                        </div>
                        <p v-if="list.manager_note">{{ list.manager_note }}</p>
                      </div>
                      <div class="col-xs-12 no-padding" v-if="list.items.note">
                        <h5><span class="label label-primary">客户留言</span></h5>
                        <p>{{ list.items.note }}</p>
                      </div>
                      <div class="col-xs-12 no-padding">
                        <div class="col-xs-6 no-padding" >
                          <h5><span class="label label-primary">出发地</span></h5>
                          <p>{{ list.from.adr }}</p>
                          <p>
                            <span v-if="list.from.fbtype == 1">{{ $t('offer.buildingtype1') }}</span>
                            <span v-else-if="list.from.fbtype == 2">{{ $t('offer.buildingtype2') }}</span>
                            <span v-else-if="list.from.fbtype == 3">{{ $t('offer.buildingtype3') }}</span>
                            <span v-else-if="list.from.fbtype == 4">{{ $t('offer.buildingtype4') }}</span>
                            <span v-else-if="list.from.fbtype == 6">{{ $t('offer.buildingtype6') }}</span>
                            <span v-else-if="list.from.fbtype == 5">{{ $t('offer.buildingtype5') }}</span>
                            <span v-if="list.from.elevator == 1"> {{ $t('event.elevator') }}</span>
                            <span v-else-if="list.from.elevator == 2"> {{ $t('event.stairs') }}</span>
                            <span v-else> {{ $t('event.elevatorAndstairs') }}</span>
                            <span v-if="list.from.floor"> {{ list.from.floor }}层</span>
                          </p>
                        </div>
                        <div class="col-xs-6 no-padding">
                          <h5><span class="label label-primary">目的地</span></h5>
                          <p>{{ list.to.adr }}</p>
                          <p>
                            <span v-if="list.to.tbtype == 1">{{ $t('offer.buildingtype1') }}</span>
                            <span v-else-if="list.to.tbtype == 2">{{ $t('offer.buildingtype2') }}</span>
                            <span v-else-if="list.to.tbtype == 3">{{ $t('offer.buildingtype3') }}</span>
                            <span v-else-if="list.to.tbtype == 4">{{ $t('offer.buildingtype4') }}</span>
                            <span v-else-if="list.to.tbtype == 6">{{ $t('offer.buildingtype6') }}</span>
                            <span v-else-if="list.to.tbtype == 5">{{ $t('offer.buildingtype5') }}</span>
                            <span v-if="list.to.elevator == 1"> {{ $t('event.elevator') }}</span>
                            <span v-else-if="list.to.elevator == 2"> {{ $t('event.stairs') }}</span>
                            <span v-else> {{ $t('event.elevatorAndstairs') }}</span>
                            <span v-if="list.to.floor"> {{ list.to.floor }}层</span>
                          </p>
                        </div>
                      </div>
                      <div class="col-xs-12 no-padding">
                        <h5><span class="label label-success">家具</span></h5>
                        <div class="col-xs-6" v-if="list.items.bed1">
                          ベッド（ダブル）: {{ list.items.bed1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.bed2">
                          ベッド（セミダブル）: {{ list.items.bed2 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.bed3">
                          ベッド（シングル）: {{ list.items.bed3 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.sofa3">
                          ソファー（3人）: {{ list.items.sofa3 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.sofa2">
                          ソファー（2人）: {{ list.items.sofa2 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.sofa1">
                          ソファー（1人）: {{ list.items.sofa1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.cupboard">
                          食器棚: {{ list.items.cupboard }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.stand">
                          サイドボード・テレビ台: {{ list.items.stand }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.drawer1">
                          タンス類（大）: {{ list.items.drawer1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.drawer2">
                          タンス類（小）: {{ list.items.drawer2 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.cases1">
                          衣装ケース: {{ list.items.cases1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.bookshelf">
                          本棚: {{ list.items.bookshelf }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.diningtable">
                          ダイニングテーブル: {{ list.items.diningtable }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.desk">
                          リビングテーブル・机: {{ list.items.desk }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.kotatsu">
                          こたつ: {{ list.items.kotatsu }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.dresser">
                          ドレッサー: {{ list.items.dresser }}
                        </div>
                      </div>
                      <div class="col-xs-12 no-padding">
                        <h5><span class="label label-success">家电</span></h5>
                        <div class="col-xs-6" v-if="list.items.tv1">
                          テレビ（40以上）: {{ list.items.tv1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.tv2">
                          テレビ（40未満）: {{ list.items.tv2 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.stereo">
                          コンポ、ステレオ: {{ list.items.stereo }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.pc">
                          パソコン: {{ list.items.pc }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.lighting">
                          照明器具: {{ list.items.lighting }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.stove">
                          ストーブ、ヒーター: {{ list.items.stove }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.refrigerator1">
                          冷蔵庫（3ドア以上）: {{ list.items.refrigerator1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.refrigerator2">
                          冷蔵庫（2ドア）: {{ list.items.refrigerator2 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.microwave">
                          電子レンジ: {{ list.items.microwave }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.drier">
                          乾燥機: {{ list.items.drier }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.washing1">
                          洗濯機（タテ）: {{ list.items.washing1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.washing2">
                          洗濯機（ドラム）: {{ list.items.washing2 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.aircondition1">
                          エアコン拆除: {{ list.items.aircondition1 }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.aircondition2">
                          エアコン安装: {{ list.items.aircondition2 }}
                        </div>
                      </div>
                      <div class="col-xs-12 no-padding">
                        <h5><span class="label label-success">其他</span></h5>
                        <div class="col-xs-6" v-if="list.items.futon">
                          布団: {{ list.items.futon }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.carpet">
                          カーペット: {{ list.items.carpet }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.plants">
                          植木・観葉植物: {{ list.items.plants }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.altar">
                          仏壇: {{ list.items.altar }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.bicycle">
                          自転車: {{ list.items.bicycle }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.bike">
                          バイク: {{ list.items.bike }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.piano">
                          ピアノ・エレクトーン: {{ list.items.piano }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.suitcase">
                          スーツケース(行李箱): {{ list.items.suitcase }}
                        </div>
                        <div class="col-xs-6" v-if="list.items.cardboard">
                          ダンボール: {{ list.items.cardboard }}<span v-if="list.items.cardboard > 9">+</span>
                        </div>
                      </div>
                      <div class="col-xs-12 no-padding">
                        <h5><span class="label label-success">服务</span></h5>
                        <div class="col-xs-6" v-if="list.otherservice.kongtiao">
                          空调移装
                        </div>
                        <div class="col-xs-6" v-if="list.otherservice.wangluo">
                          新家安装网络
                        </div>
                        <div class="col-xs-6" v-if="list.otherservice.maizg">
                          想买中古品
                        </div>
                        <div class="col-xs-6" v-if="list.otherservice.maihuo">
                          想卖中古品
                        </div>
                        <div class="col-xs-6" v-if="list.otherservice.chuli">
                          粗大ゴミ处理
                        </div>
                        <div class="col-xs-6" v-if="list.otherservice.qingsao">
                          退室清扫
                        </div>
                      </div>
                      <div v-if="list.images && list.images.length > 0" class="gallery col-xs-12 no-padding">
                        <div v-for="imgfile in list.images" class="col-xs-2 no-padding marginb8">
                          <a :href="'/uploads/' + imgfile | toBiggerImg" class="thumbnail" :title="imgfile | truncate(25)">
                            <img :src="'/uploads/' + imgfile" :alt="imgfile">
                          </a>
                          <div class='text-right' v-if="!list.event_id">
                            <button type="button" class="btn btn-xs btn-default" @click="delImg(list.id, imgfile)">{{ $t('global.remove') }}</button>
                          </div>
                        </div>
                      </div>
										</td>
									</tr>
								</template>
							</template>
							<template v-else>
								<tr>
									<td colspan="6"><span id="listrs">{{ $t('global.loading') }}</span></td>
								</tr>
							</template>
						</tbody>
					</table>
					<div class="marginlr10 pull-right">
						<pagination :data="estimates" @pagination-change-page="get_estimates"></pagination>
					</div>
				</div>
			</div>
			<!-- /.row (main row) -->
		</section>
		<!-- /.content -->
    <transition name="modal">
      <div v-show="showNoteModal" class="modal-mask form-horizontal">
        <div class="modal-wrapper">
          <div class="modal-container">

            <div class="modal-header">
              <slot name="header">
                {{ $t('estimate.note') }}
              </slot>
            </div>

            <div class="modal-body">
              <slot name="body">
                <textarea rows="3" v-model="estimate.note" :placeholder="$t('estimate.note')" class="form-control"></textarea>
                <upload-file :fileAccept="fileAccept" @update-file="updateFiles" @update-loading="setLoading" @error-push="errorPush"></upload-file>
                <div v-show="hasFile" class='col-xs-12 no-padding'>
                  <div class="gallery">
                    <div v-for="(file, key) in estimate.images" class="col-xs-3 no-padding marginb8">
                      <a :href="'/uploads/' + file" class="thumbnail" :title="file | truncate(25)">
                        <img :src="'/uploads/' + estimate.thumbs[key]" :alt="file">
                      </a>
                      <div class='text-right'>
                        <button type="button" class="btn btn-xs btn-default" @click="removeFile(estimate.thumbs[key])">{{ $t('global.remove') }}</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="errors.length">
                  <ul>
                    <li v-for="error in errors">{{ error }}</li>
                  </ul>
                </div>
              </slot>
            </div>

            <div class="modal-footer text-left">
              <slot name="footer">
                <button class="btn btn-xs btn-default" @click="showNoteModal = false">
                  {{ $t('global.Cancel') }}
                </button>
                <button class="btn btn-xs btn-primary pull-right" @click="updatenote">
                  {{ $t('global.Submit') }}
                </button>
              </slot>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <loading :loadingShow="loadingShow"></loading>
    <quick-form v-if="showformflag" :eventid="eventid" :formoptions="formoptions" :estimatedate="estimatedate" @closeform="quickformswitch(false)" @addedevent="addedevent"></quick-form>
	</div>
</template>

<script>
import SampleNav from '../Public/SampleNav'
import UploadFile from '../Public/UploadFile'
import Loading from '../Public/Loading'
import moment from 'moment'
import baguetteBox from 'baguettebox.js'
import pagination from 'laravel-vue-pagination'
import QuickForm from '../Events/QuickForm.vue';

export default {
	data() {
		return {
			estimates: {
				data: {
					length: 0
				}
			},
      estimate: {
        id: 0,
        note: "",
        images: [],
        thumbs: []
      },
      formoptions: {
        worktype: [],
        careful: [],
        total: [],
        aboutgoods: [],
        truck: [],
        product_list: []
      },
      estimatedate: {},
      eventid: "",
      holidays: [],
      showNoteModal: false,
      showformflag: false,
      hasFile: false,
      loadingShow: false,
      errors: [],
      fileAccept: "image/*",
			type: 2
		}
	},
	mounted() {
		this.get_estimates();
    this.get_formtypes();
	},
	methods: {
    createEvent(listid) {
			this.eventid = "";
      this.get_list_for_form();
      for(var i in this.estimates.data) {
        if(this.estimates.data[i].id == listid) {
          this.estimatedate = this.estimates.data[i];
        }
      }
      this.showformflag = true;
    },
    get_list_for_form(eventid = 0) {
      this.formoptions.product_list = [];
      this.$http({
        url: '/api/get_productlist/' + eventid,
        method: 'GET'
      }).then(res =>  {
        for (var index in res.data.productlists) {
          var option = {};
          option.id = res.data.productlists[index].id;
          option.text = '#'+res.data.productlists[index].id+' '+this.$parent.$options.methods.formatNumberJPY(res.data.productlists[index].price);
          this.formoptions.product_list.push(option);
        }
      })
    },
    editevent(eventid) {
      this.eventid = eventid;
      this.get_list_for_form(eventid);
      this.showformflag = true;
    },
    addedevent() {
			this.get_estimates()
      this.quickformswitch(false)
    },
    quickformswitch(flg) {
			this.showformflag = flg;
		},
		showDetail(e) {
			$(e.target).closest('tr').next().toggleClass('open');
			$(e.target).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
		},
		get_estimates(page = 1) {
      this.estimates.data.length = 0;
			$('.open').toggleClass('open');
			$('.fa-angle-double-up').toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
			this.$http({
				url: '/admin/estimates/' + this.type + '?page=' + page,
				method: 'GET'
			}).then(res =>  {
				if(res.data.offers.data.length == 0) {
					$('#listrs').html(this.$t('global.noRes'));
				} else {
          this.holidays = res.data.holidays;
					this.estimates = res.data.offers;
					// this.$nextTick(function () {
					// 	for(var index in classname) {
					// 		baguetteBox.run(classname[index].key, {
					// 			noScrollbars: true,
					// 			captions: function(element) {
					// 				return "<a href='https://shop.koyoshieki.com/wp-admin/edit.php?s="+classname[index].sku+"&post_type=product' target='_blank'><button type='button' class='btn btn-primary'>"+i18ndrop+"</button></a>"
					// 			}
					// 		});
					// 	}
					// })
				}
			})
		},
    deletelist(listid) {
      if(window.confirm(this.$t('productlist.delwarning'))) {
        this.$http.delete('/admin/productlist/'+listid).then(response => {
          if(response) {
            $('.open').toggleClass('open');
			      $('.fa-angle-double-up').toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
            for(var key in this.product_lists.data) {
              if(this.product_lists.data[key].id == listid){
                this.product_lists.data.splice(key, 1);
              }
            }
          }
        })
      }
    },
    showNote(id) {
      for(var key in this.estimates.data) {
        if(this.estimates.data[key].id == id) {
          this.estimate.note = this.estimates.data[key].manager_note;
          this.estimate.id = id;
        }
      }
      this.showNoteModal = true;
    },
    updatenote() {
      if(this.estimate.id) {
        this.$http.put('/admin/estimate/' + this.estimate.id, this.estimate).then(response => {
          for(var key in this.estimates.data) {
            if(this.estimates.data[key].id == this.estimate.id) {
              this.estimates.data[key] = response.data;
            }
          }
          this.estimate.images = [];
          this.estimate.thumbs = [];
          this.showNoteModal = false;
          this.loadingShow = false;
        }).catch(error => {
          this.errors.push(this.$t('global.calltheadministrator'));
          this.loadingShow = false;
        });
      }
    },
    updateFiles(file) {
      for(var i in file) {
        var bigfile = file[i].replace("_thumb", "");
        this.estimate.images.push(bigfile);
        this.estimate.thumbs.push(file[i]);
      }
      if(this.estimate.thumbs.length > 0) {
        this.hasFile = true;
      } else {
        this.hasFile = false;
      }
    },
    removeFile(file) {
      if(window.confirm('Are you sure remove this photo?' + file)) {
        var bigfile = file.replace("_thumb", "");
        var index = this.estimate.images.indexOf(bigfile);
        this.estimate.images.splice(index, 1);
        this.estimate.thumbs.splice(index, 1);
      }
    },
    delImg(id, img) {
      if(window.confirm('Are you sure remove this photo?' + img)) {
        this.$http.put('/admin/estimate/' + id, {img: img}).then(response => {
          for(var key in this.estimates.data) {
            if(this.estimates.data[key].id == id) {
              var index = this.estimates.data[key].images.indexOf(img);
              this.estimates.data[key].images.splice(index, 1);
            }
          }
          this.loadingShow = false;
        }).catch(error => {
          alert(this.$t('global.calltheadministrator'));
          this.loadingShow = false;
        });
      }
    },
    setLoading(switchTF) {
      this.loadingShow = switchTF;
    },
    errorPush(message) {
      this.errors.push(message);
    },
    get_formtypes() {
      this.$http({
        url: '/api/get_types/',
        method: 'GET'
      }).then(res =>  {
        for (var index in res.data.types) {
          var group_id = res.data.types[index].group_id;
          var option = {};
          option.id = res.data.types[index].id;
          option.text = res.data.types[index].name;
          switch (+group_id) {
            case 1:
              this.formoptions.worktype.push(option);
              break;
            case 2:
              this.formoptions.careful.push(option);
              break;
            case 3:
              this.formoptions.total.push(option);
              break;
            case 4:
              this.formoptions.aboutgoods.push(option);
              break;
            case 5:
              this.formoptions.truck.push(option);
              break;
            default:
              break;
          }
        }
      })
    },
    formatDateWithWeekname(date) {
      if(this.$i18n.locale == 'cn') {
        moment.locale('cn', {weekdays: ["日","一","二","三","四","五","六"]});
        var weekname = moment(date).format('星期dddd');
        moment.locale('ja', {weekdays: ["日","月","火","水","木","金","土"]});
        weekname += moment(date).format('(dddd)');
      } else {
        moment.locale('ja');
        weekname = moment(date).format('dddd');
      }
      if(this.holidays && this.holidays[date]) {
        weekname += ' '+this.holidays[date];
      }

      return moment(date).format('YYYY-MM-DD') + ' ' + weekname;
    }
	},
  updated: function () {
    this.$nextTick(function () {
      // if(this.images.length > 0) {
        baguetteBox.run('.gallery');
      // }
    })
  },
  filters: {
    truncate: function (value, length) {
      if (!value) return ''
      var length = length ? parseInt(length, 10) : 20
      if(value.length <= length) {
        return value
      }
      else {
        return '...' + value.substring(value.length - length)
      }
    },
    toBiggerImg(filename) {
      return filename.replace("_thumb", "");
    }
  },
	watch: {
		type: function() {
			this.get_estimates();
		}
	},
	components: {
		SampleNav,
		pagination,
    UploadFile,
    QuickForm,
    Loading
	}
}
</script>
