<template>
	<div class="row col-md-12">
		<section class="content-header">
			<h1>
				{{ $t('productlist.pagetitle') }}
				<small>{{ $t('productlist.subtitle') }}</small>
			</h1>
		</section>
		<!-- Main content -->
		<section class="content" v-if="!showformflag">
			<!-- Main row -->
			<sample-nav :linkurl="'/admin/productlist/create'"></sample-nav>
			<div class="box">
				<div class="box-header">
					<label :class="{ checked: type == 1 }">
						<input type="radio" value="1" v-model="type">
						{{ $t('productlist.alltype') }}
					</label>
					<label :class="{ checked: type == 2 }">
						<input type="radio" value="2" v-model="type">
						{{ $t('productlist.untreated') }}
					</label>
					<label :class="{ checked: type == 3 }">
						<input type="radio" value="3" v-model="type">
						{{ $t('productlist.treated') }}
					</label>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<th style="width: 30px">#</th>
								<th style="width: 50px">{{ $t('global.details') }}</th>
								<th>{{ $t('global.createduname') }}</th>
								<th>{{ $t('global.updatedat') }}</th>
								<th>{{ $t('productlist.total') }}</th>
								<th style="width: 10px"><i class="fa fa-flag"></i></th>
							</tr>
							<template v-if="product_lists.data.length > 0">
								<template v-for="(list, index) in product_lists.data">
									<tr>
										<td>{{ list.id }}</td>
										<td>
											<div class="zy-icon">	
												<span class="bigger-140 show-details-btn" @click="showDetail" title="Show Details">
													<i class="fa fa-angle-double-down"></i>
												</span>
											</div>
										</td>
										<td>{{ list.user.name }}</td>
										<td>{{ list.updated_at }}</td>
										<td>{{ list.price | formatNumberJPY }}</td>
										<td><span v-if="list.event_id"><i class="fa fa-check-square-o"></i></span></td>
									</tr>
									<tr class="detail-row">
										<td colspan="6">
											<div class="detailtitle">
												<span>#{{ list.id }}</span> / 
												<span>{{ $t('productlist.cost') }}{{ list.cost | formatNumberJPY }}</span> / 
												<span>{{ $t('productlist.totalprice') }}{{ list.price | formatNumberJPY }}</span> / 
												<span v-if="list.event_id" @click="editevent(list.event_id)" class="blue">{{ $t('event.event') }} {{ list.event.event_date}} #{{ list.event.id }}</span>
												<span v-else>{{ $t('productlist.untreated') }} <i class="fa fa-trash-o red" @click="deletelist(list.id)"></i></span>
												<div class="pull-right">
													<button class="btn btn-xs btn-warning" @click="$router.push('/admin/productlist/' + list.id + '/edit')">
														<i class="fa fa-pencil"></i>
													</button>
													<button v-if="!list.event_id" class="btn btn-xs btn-primary" @click="createEvent(list.id)">
														<i class="fa fa-calendar"></i>
													</button>
												</div>
											</div>
											<table class="table table-striped">
												<tbody>
													<tr>
														<th style="width: 50px">SKU</th>
														<th>{{ $t('productlist.title') }}</th>
														<th style="width: 50px">{{ $t('productlist.quantity') }}</th>
														<th style="width: 70px">{{ $t('productlist.price') }}</th>
														<th style="width: 10px"></th>
													</tr>
													<tr v-for="(item, i) in list.items">
														<td>{{ item.sku }}</td>
														<td>{{ item.title }}</td>
														<td>{{ item.quantity }}</td>
														<td>{{ item.price | formatNumberJPY }}</td>
														<td>
															<div class="gallery">
																<a :href="item.image">
																	<i v-if="item.image" :atl="item.title" :data-sku="item.sku" class="fa fa-image bigger-120"></i>
																</a>
															</div>
														</td>
													</tr>
													<tr v-if="list.note"><td colspan="5">{{ $t('productlist.note') }}:{{ list.note }}</td></tr>
												</tbody>
											</table>
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
						<pagination :data="product_lists" @pagination-change-page="get_productlists"></pagination>
					</div>
				</div>
			</div>
			<!-- /.row (main row) -->
		</section>
		<!-- /.content -->
    <quick-form v-if="showformflag" :eventid="eventid" :formoptions="formoptions" :productlistid="productlistid" @closeform="quickformswitch(false)" @addedevent="addedevent"></quick-form>
	</div>
</template>

<script>
import SampleNav from '../Public/SampleNav'
import baguetteBox from 'baguettebox.js'
import pagination from 'laravel-vue-pagination'
import QuickForm from '../Events/QuickForm.vue';

export default {
	data() {
		return {
			product_lists: {
				data: {
					length: 0
				}
			},
			type: 2,
      showformflag: false,
      productlistid: "",
			eventid: "",
      formoptions: {
        worktype: [],
        careful: [],
        total: [],
        aboutgoods: [],
        truck: [],
        product_list: []
      }
		}
	},
	mounted() {
		this.get_productlists()
    this.get_formtypes()
	},
	methods: {
		showDetail(e) {
			$(e.target).closest('tr').next().toggleClass('open');
			$(e.target).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
		},
    createEvent(listid) {
			this.eventid = "";
      this.get_list_for_form();
      this.productlistid = listid;
      this.showformflag = true;
    },
		editevent(eventid) {
      this.eventid = eventid;
      this.get_list_for_form(eventid);
      this.showformflag = true;
    },
    addedevent() {
			this.get_productlists()
      this.quickformswitch(false)
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
		get_productlists(page = 1) {
      this.product_lists.data.length = 0;
			$('.open').toggleClass('open');
			$('.fa-angle-double-up').toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
			this.$http({
				url: '/admin/pdlist/getlists/' + this.type + '?page=' + page,
				method: 'GET'
			}).then(res =>  {
				if(res.data.data.length == 0) {
					$('#listrs').html(this.$t('global.noRes'));
				} else {
					this.product_lists = res.data;
					var i18ndrop = this.$t('productlist.drop');
					this.$nextTick(function () {
						baguetteBox.run('.gallery', {
							noScrollbars: true,
							captions: function(element) {
								return "<a href='https://shop.koyoshieki.com/wp-admin/edit.php?s="+$(element).find('i').data('sku')+"&post_type=product' target='_blank'><button type='button' class='btn btn-primary'>"+i18ndrop+"</button></a>"
							}
						});
					})
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
		quickformswitch(flg) {
			this.showformflag = flg;
		},
    get_formtypes() {
      this.$http({
        url: '/api/get_types',
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
    }
	},
	watch: {
		type: function() {
			this.get_productlists();
		}
	},
	filters: {
		formatNumberJPY(number) {
			if(number) {
				return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
			}
		}
	},
	components: {
		SampleNav,
    QuickForm,
		pagination
	}
}
</script>
