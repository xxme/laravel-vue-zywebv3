<template>
  <div class="row col-md-12">
    <section class="content-header">
			<h1>
				{{ $t('finance.moneymanage') }}
				<small>{{ $t('finance.moneymanage') }}</small>
			</h1>
		</section>
		<!-- Main content -->
		<section class="content">
      <sample-nav></sample-nav>
			<div class="box">
        <div class="col-xs-12" v-if="type == 1">
          <h5>未入金统计</h5>
          <table class="table">
            <tbody>
              <tr v-for="(price, name) in user">
              <td>{{ name }}</td>
              <td>{{ price | formatNumberJPY }}</td>
              </tr>
            </tbody>
          </table>
        </div>
				<div class="box-header">
					<label :class="{ checked: type == 2 }">
						<input type="radio" value="2" v-model="type">
						{{ $t('event.paymented') }}
					</label>
					<label :class="{ checked: type == 1 }">
						<input type="radio" value="1" v-model="type">
						{{ $t('event.notpayment') }}
					</label>
          <span v-if="checkedAmount > 0">{{ $t('finance.checked') }}{{ checkedAmount | formatNumberJPY }}</span>
          <button class="btn btn-xs btn-primary pull-right" @click="changestatus()">{{ $t('nav.money') }}</button>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<tbody>
							<tr>
                <th style="width: 30px"><span v-if="type == 1">#</span><span v-else><i class="fa fa-flag"></i></span></th>
								<th style="width: 120px">{{ $t('event.payee') }}</th>
								<th>{{ $t('finance.completeDate') }}</th>
                <th>{{ $t('finance.amount') }}</th>
							</tr>
              <template v-if="finances.data.length > 0">
                <tr v-for="(list, index) in finances.data">
                  <td v-if="type == 1"><input type="checkbox" :value="list.id" v-model="checkedIds" v-if="list.status == 1"></td>
                  <td v-else><i class="fa fa-check-square-o"></i></td>
                  <td>
                    {{ list.user.name }}
                  </td>
                  <td>{{ list.created_at }}</td>
                  <td>{{ list.finalprice | formatNumberJPY }}</td>
                </tr>
              </template>
              <template v-else>
								<tr>
									<td colspan="6"><span id="listrs">{{ $t('global.loading') }}</span></td>
								</tr>
							</template>
            </tbody>
          </table>
          <div class="marginlr10 pull-right">
						<pagination :data="finances" @pagination-change-page="getList"></pagination>
					</div>
        </div>
      </div>
    </section>
    <loading :loadingShow="loadingShow"></loading>
  </div>
</template>

<script>
import SampleNav from '../Public/SampleNav'
import Loading from '../Public/Loading'
import pagination from 'laravel-vue-pagination'

export default {
  mounted() {
		this.getList();
	},
  data () {
    return {
      type: 1,
      checkedIds: [],
      checkedAmount: 0,
      loadingShow: false,
      user: [],
      finances: {
        data: {
          length: 0
        }
      }
    }
  },
  methods: {
    getList(page = 1) {
      this.finances.data.length = 0;
      this.$http({
        url: '/admin/events/finances/' + this.type + '?page=' + page,
        method: 'GET'
      }).then(res =>  {
        if(res.data == '403') {
          alert(this.$t('global.manageronly'));
          this.$router.push('/admin');
        }
        if(res.data.data.data.length == 0) {
          $('#listrs').html(this.$t('global.noRes'));
        } else {
          this.finances = res.data.data;
          this.user = res.data.user;
        }
      })
    },
    changestatus() {
      if(this.checkedIds.length > 0) {
        var self = this;
        var message = this.$t('finance.areyousurereceived') + this.$parent.$options.methods.formatNumberJPY(this.checkedAmount);
        if(window.confirm(message)) {
          this.loadingShow = true;
          this.$http.post('/admin/event/received', this.checkedIds).then(response => {
            this.checkedIds = [];
            this.getList();
            this.loadingShow = false;
          }).catch(error => {
            alert(this.$t('global.calltheadministrator'));
            this.loadingShow = false;
          });
        }
      } else {
        alert(this.$t('global.notselectd'));
      }
    }
  },
  watch: {
		type: function() {
			this.getList();
		},
    checkedIds: function() {
      if(this.checkedIds.length > 0) {
        var amount = 0;
        for(var key in this.finances.data) {
          if(this.checkedIds.indexOf(this.finances.data[key].id) !== -1) {
            amount += this.finances.data[key].finalprice
          }
        }
        this.checkedAmount = amount;
      } else {
        this.checkedAmount = 0;
      }
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
		pagination,
    Loading
	}
}
</script>
