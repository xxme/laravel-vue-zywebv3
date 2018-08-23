<template>
	<div>
		<section class="content-header">
			<h1>
				{{ $t('productlist.pagetitle') }}
				<small>{{ $t('productlist.subtitle') }}</small>
			</h1>
		</section>
		<!-- Main content -->
		<section class="content">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ boxtitle }} <small v-if="$route.params.listid">#{{$route.params.listid}}</small></h3>
					<span class="bigger-140 pull-right" @click="addRow"><i class="fa fa-plus-square blue"></i></span>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<th style="width: 66px">SKU</th>
								<th>{{ $t('productlist.title') }}</th>
								<th style="width: 50px">{{ $t('productlist.quantity') }}</th>
								<th style="width: 70px">{{ $t('productlist.price') }}</th>
								<th style="width: 40px"><i class="fa fa-cog"></i></th>
							</tr>
							<tr v-for="(row, index) in rows">
								<td><input type="number" v-model.lazy="row.sku" class="form-control"></td>
								<td><input type="text" v-model="row.title" class="form-control"></td>
								<td><input type="number" v-model.number="row.quantity" class="form-control"></td>
								<td><input type="number" v-model.number="row.price" class="form-control"></td>
								<td>
									<div :class="'gallery'+index">
										<a :href="row.image">
											<i v-if="row.image" :atl="row.title" class="fa fa-image bigger-120"></i>
										</a>
									</div>
									<i class="fa fa-trash-o btn-box-tool red actioncursor bigger-120" @click="removeElement(index)"></i>
								</td>
							</tr>
							<tr v-show="rows.length > 0"><td colspan="5"><textarea rows="3" v-model="note" :placeholder="$t('productlist.note')" class="form-control"></textarea></td></tr>
							<tr>
								<td class="pricetd"><span v-show="rows.length > 0">{{ $t('productlist.cost') }}</span></td>
								<td>
									<div class="col-xs-6 no-padding">
										<span v-show="rows.length > 0"><input type="number" class="form-control" v-model.number="cost"></span>
									</div>
									<div class="costcontrol col-xs-4">
										<i v-show="rows.length > 0" class="fa fa-plus-square blue actioncursor bigger-140" @click="cost += 1000"></i>
										<i v-show="rows.length > 0" class="fa fa-minus-square blue actioncursor bigger-140" @click="cost > 1000 ? cost -= 1000 : cost = 0"></i>
									</div>
								</td>
								<td class="pricetd"><span v-show="total > 0">{{ $t('productlist.totalprice') }}</span></td>
								<td class="pricetd"><span v-show="total > 0">{{ total | formatNumberJPY }}</span></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<div class="col-xs-12">
						<span v-if="error">{{ error }}</span>
					</div>
					<div class="col-xs-12 form-group">
						<button type="button" class="btn btn-default" @click="$router.go(-1)">{{ $t('global.Cancel') }}</button>
						<button type="button" class="btn btn-primary pull-right" @click="submitForm">{{ $t('global.Submit') }}</button>
					</div>
				</div>
				<loading :loadingShow="loadingShow"></loading>
			</div>
		</section>
	</div>
</template>

<script>
import baguetteBox from 'baguettebox.js'
import Loading from '../Public/Loading'
export default {
	mounted() {
    if (this.$route.params.listid){
			this.getProductList(this.$route.params.listid);
		}
  },
	data() {
    return {
			boxtitle: this.$route.params.listid ? this.$t('global.edit') : this.$t('global.add'),
			id: "",
			rows: [],
			oldRows: [],
			error: "",
			cost: 0,
			totalprice: 0,
			note: "",
			i18ndrop: this.$t('productlist.drop'),
			loadingShow: false
		}
	},
	watch: {
		rows: {
			handler: function(newValue) {
				for(var index in this.rows) {
					if(newValue[index].sku && newValue[index].sku > 9999 
							&& (!this.oldRows[index] || this.oldRows[index].sku != newValue[index].sku)) {
						this.getProduct(index, newValue[index].sku);
						this.oldRows = _.cloneDeep(this.rows);
					}
				}
			},
			deep: true
		}
	},
	computed: {
		total: function () {
			var total = 0;
			for(var index in this.rows) {
				total += parseInt(this.rows[index].price) * parseInt(this.rows[index].quantity);
			}
			this.totalprice = total += parseInt(this.cost);
			return total;
		}
	},
	methods: {
		addRow: function() {
			var elem = document.createElement('tr');
			this.rows.push({
				sku: "",
				title: "",
				quantity: "1",
				price: "",
				image: ""
			});
		},
		removeElement: function(index) {
			if(window.confirm(this.$t('global.areYouSure'))) {
				this.rows.splice(index, 1);
			}
		},
		checkForm() {
			var checkflg = true;
			for(var index in this.rows) {
				if(!this.rows[index].price || !this.rows[index].title || !this.rows[index].quantity) {
					checkflg = false;
				}
			}
			return checkflg;
		},
		submitForm() {
			this.error = "";
			if(this.rows.length == 0) {
				this.error = this.$i18n.t('productlist.norow');
				return;
			}
			if(this.checkForm()) {
				this.loadingShow = true;
				var submitdata = new Object();
				submitdata.items = this.rows;
				submitdata.cost = this.cost;
				submitdata.totalprice = this.totalprice;
				submitdata.note = this.note;
				if(this.id) {
					this.$http.put('/admin/productlist/' + this.id, submitdata).then(response => {
						this.$router.go(-1);
						this.loadingShow = false;
					}).catch(error => {
						this.errors.push(this.$t('global.calltheadministrator'));
						this.loadingShow = false;
					});
				} else {
					this.$http.post('/admin/productlist', submitdata).then(response => {
						this.$router.go(-1);
						this.loadingShow = false;
					}).catch(error => {
						this.error = this.$i18n.t('global.calltheadministrator');
						this.loadingShow = false;
					});
				}
			} else {
				this.error = this.$i18n.t('productlist.required');
			}
		},
		getProductList(listid) {
			this.$http({
        url: '/admin/productlist/' + listid,
        method: 'GET'
      }).then(res =>  {
        this.id = listid;
				this.cost = res.data.cost;
				this.totalprice = res.data.price;
				this.note = res.data.note;
        for(var key in res.data.items) {
					var item = new Object();
					item.sku = res.data.items[key].sku;
					item.title = res.data.items[key].title;
					item.price = res.data.items[key].price;
					item.quantity = res.data.items[key].quantity;
					item.image = res.data.items[key].image;
					this.rows.push(item);
				}
				this.oldRows = _.cloneDeep(this.rows);
      })
		},
		getProduct(index, sku) {
			console.log("getProduct");
			this.error = "";
			this.loadingShow = true;
			this.$http.get('/api/get_product/' + sku,).then(response => {
				if(response.data) {
					this.rows[index].title = response.data.title;
					this.rows[index].price = response.data.price;
					if(response.data.image) {
						this.rows[index].image = response.data.image;
						var classname = '.gallery' + index;
						var i18ndrop = this.i18ndrop;
						this.$nextTick(function () {
							baguetteBox.run(classname, {
								noScrollbars: true,
								captions: function(element) {
									return "<a href='https://shop.koyoshieki.com/wp-admin/edit.php?s="+sku+"&post_type=product' target='_blank'><button type='button' class='btn btn-primary'>"+i18ndrop+"</button></a>"
								}
							});
						})
					}
				} else {
					this.error = this.$i18n.t('productlist.couldNotFind') + '\n#SKU"'+ sku +'"';
				}
				this.loadingShow = false;
			}).catch(error => {
				this.error = this.$i18n.t('global.calltheadministrator');
				this.loadingShow = false;
			});
		}
	},
	components: {
    Loading
  },
	filters: {
    formatNumberJPY(number) {
      if(number) {
        return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
      }
    }
  },
	updated: function () {
		var i18ndrop = this.i18ndrop;
    this.$nextTick(function () {
      for(var key in this.rows) {
				var sku = this.rows[key].sku;
				baguetteBox.run('.gallery'+key, {
					noScrollbars: true,
					captions: function(element) {
						return "<a href='https://shop.koyoshieki.com/wp-admin/edit.php?s="+sku+"&post_type=product' target='_blank'><button type='button' class='btn btn-primary'>"+i18ndrop+"</button></a>"
					}
				});
			}
    })
  }
}
</script>

<style scoped>
.form-control {
	padding: 6px;
}
.pricetd {
	padding: 16px 8px;
	text-align: right;
}
.costcontrol i {
	margin-left: 3px;
}
</style>