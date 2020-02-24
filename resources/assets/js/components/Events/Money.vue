<template>
  <div class="row col-md-12">
    <section class="content-header">
			<h1>
				{{ $t('finance.moneymanage') }}
				<small>{{ $t('finance.moneymanage') }}</small>
			</h1>
		</section>
		<!-- Main content -->
		<section class="content" v-show="!showEventItem">
      <sample-nav></sample-nav>
			<div class="box">
        <div class="col-xs-12" v-if="type == 1">
          <h4>{{ $t('event.finalprice') }}{{ $t('event.notpayment') }}</h4>
          <table class="table">
            <tbody>
              <tr v-for="(price, name) in user">
                <td>{{ name }}</td>
                <td>{{ price | formatNumberJPY }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-xs-12" v-if="type == 3">
          <h4>{{ $t('event.depositjpyL') }}{{ $t('event.notpayment') }}</h4>
          <p>{{ finances.sum | formatNumberJPY }}</p>
        </div>
				<div class="box-header">
					<label :class="{ checked: type == 2 }">
						<input type="radio" value="2" v-model="type">
						{{ $t('event.finalprice') }}{{ $t('event.paymented') }}
					</label>
					<label :class="{ checked: type == 1 }">
						<input type="radio" value="1" v-model="type">
						{{ $t('event.finalprice') }}{{ $t('event.notpayment') }}
					</label>
          <label :class="{ checked: type == 4 }">
						<input type="radio" value="4" v-model="type">
						{{ $t('event.depositjpyL') }}{{ $t('event.paymented') }}
					</label>
          <label :class="{ checked: type == 3 }">
						<input type="radio" value="3" v-model="type">
						{{ $t('event.depositjpyL') }}{{ $t('event.notpayment') }}
					</label>
          <span v-if="checkedAmount != 0">{{ $t('finance.checked') }}{{ checkedAmount | formatNumberJPY }}</span>
          <button class="btn btn-xs btn-primary pull-right" @click="changestatus()">{{ $t('nav.money') }}</button>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<tbody>
							<tr>
                <th style="width: 30px"><span v-if="type == 1">#</span><span v-else><i class="fa fa-flag"></i></span></th>
								<th style="width: 110px" v-if="type < 3">
                  <template v-if="type < 3">{{ $t('event.payee') }}</template>
                  <template v-else>{{ $t('event.depositjpyL') }}</template>
                </th>
                <th style="width: 60px">{{ $t('event.event') }}</th>
								<th style="width: 140px">
                  <template v-if="type == 1">{{ $t('finance.completeDate') }}</template>
                  <template v-else-if="type == 2">{{ $t('event.event') }}{{ $t('global.date') }}</template>
                  <template v-else>{{ $t('event.receipted_at') }}</template>
                </th>
                <th>{{ $t('finance.amount') }}</th>
                <th v-if="type < 3">{{ $t('finance.cause') }}</th>
							</tr>
              <template v-if="finances.data.length > 0">
                <tr v-for="(list, index) in finances.data">
                  <td v-if="type == 1"><input type="checkbox" :value="list.id" v-model="checkedIds" v-if="list.status == 1"></td>
                  <td v-else-if="type == 3"><input type="checkbox" :value="list.id" v-model="checkedDepositIds" v-if="list.status == 0"></td>
                  <td v-else><i class="fa fa-check-square-o"></i></td>
                  <td v-if="type < 3">
                    <template v-if="type == 1">
                      {{ list.user.name }}
                    </template>
                    <template v-else="type == 2">
                      <template v-if="list.expense">{{ list.expense.user.name }}</template>
                      <template v-else-if="list.deposit && list.deposit.type > 1">
                        {{ $t('event.depositjpyL') }}
                        <i class="fa fa-arrow-right" />
                        <template v-if="list.deposit.type == 2">
                          {{ $t('event.payment2') }}
                        </template>
                        <template v-else-if="list.deposit.type == 3">
                          {{ $t('event.payment3') }}
                        </template>
                        <template v-else>?</template>
                        <small v-if="list.deposit.receipted_at">
                          ({{ list.deposit.receipted_at }})
                        </small>
                      </template>
                    </template>
                  </td>
                  <td>
                    <span v-if="type == 1" class="blue" @click="showEvent(list.event_id)">#{{ list.event_id }}</span>
                    <span v-else-if="type == 2" class="blue" @click="showEvent(list.id)">#{{ list.id }}</span>
                    <span v-else class="blue" @click="showEvent(list.event_id)">#{{ list.event_id }}</span>
                  </td>
                  <td>
                    <template v-if="type == 1">{{ list.created_at }}</template>
                    <template v-else-if="type == 2">{{ list.event_date }}</template>
                    <template v-else>{{ list.receipted_at }}</template>
                  </td>
                  <td>
                    <template v-if="list.event && list.event.status == 3">-{{ list.expenditure | formatNumberJPY }}</template>
                    <template v-else-if="type == 1">{{ list.finalprice | formatNumberJPY }}</template>
                    <template v-else-if="type == 2">
                      <template v-if="list.status && list.status == 3">-{{ list.expenditure | formatNumberJPY }}</template>
                      <template v-if="list.expense">{{ list.expense.finalprice | formatNumberJPY }}</template>
                      <template v-else>{{ list.deposit.jpy | formatNumberJPY }}</template>
                    </template>
                    <template v-else>
                      {{ list.jpy | formatNumberJPY }}
                    </template>
                  </td>
                  <td v-if="type < 3">
                    <template v-if="type == 1">{{ list.cause }}</template>
                    <template v-else-if="list.expense">{{ list.expense.cause }}</template>
                  </td>
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
						<pagination :data="finances" :limit="2" @pagination-change-page="getList"></pagination>
					</div>
        </div>
      </div>
    </section>
    <loading :loadingShow="loadingShow"></loading>
    <event-item :eventdata="show_list" :auth="auth" :userlist="userlist" :showflag="showEventItem" :comefromfinances="true" @showFinances="showFinances" @updatecomplete="updatecomplete"></event-item>
  </div>
</template>

<script>
import SampleNav from '../Public/SampleNav'
import Loading from '../Public/Loading'
import pagination from 'laravel-vue-pagination'
import EventItem from './EventItem.vue';

export default {
  mounted() {
		this.getList();
	},
  data () {
    return {
      type: 1,
      checkedIds: [],
      checkedDepositIds: [],
      checkedAmount: 0,
      loadingShow: false,
      showEventItem: false,
      user: [],
      show_list: [],
      userlist: [],
      auth: null,
      finances: {
        data: {
          length: 0
        },
        sum: 0
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
        this.finances = res.data.data;
        if(res.data.data.data.length == 0) {
          $('#listrs').html(this.$t('global.noRes'));
        } else {
          this.user = res.data.user;
        }
        if (res.data.sum) {
          this.finances.sum = res.data.sum;
        }
      })
    },
    changestatus() {
      var self = this;
      var message = this.$t('finance.areyousurereceived') + this.$parent.$options.methods.formatNumberJPY(this.checkedAmount);
      if(this.type == 1 && this.checkedIds.length > 0) {
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
      } else if(this.type == 3 && this.checkedDepositIds.length > 0) {
        if(window.confirm(message)) {
          this.loadingShow = true;
          this.$http.post('/admin/event/receivedDeposit', this.checkedDepositIds).then(response => {
            this.checkedDepositIds = [];
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
    },
    showEvent(eventid) {
      this.show_list = [];
      this.$http({
        url: '/admin/event/item/' + eventid,
        method: 'GET'
      }).then(res =>  {
        this.show_list.push(res.data.events);
        this.auth = res.data.auth;
        for (var index in res.data.users) {
            var user = {};
            user.id = res.data.users[index].id;
            user.text = res.data.users[index].name;
            this.userlist.push(user);
          }
        this.showEventItem = true;
      })
    },
    updatecomplete(expense) {
      this.show_list[0].expense = expense;
      for(var index in this.finances.data) {
        if(this.finances.data[index].event_id == expense.event_id) {
          this.finances.data[index] = expense;
        }
      }
    },
    showFinances() {
      this.showEventItem = false;
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
            if (this.finances.data[key].event && this.finances.data[key].event.status == 3) {
              amount -= parseInt(this.finances.data[key].expenditure)
            } else {
              amount += parseInt(this.finances.data[key].finalprice)
            }
          }
        }
        this.checkedAmount = amount;
      } else {
        this.checkedAmount = 0;
      }
    },
    checkedDepositIds: function() {
      if(this.checkedDepositIds.length > 0) {
        var amount = 0;
        for(var key in this.finances.data) {
          if(this.checkedDepositIds.indexOf(this.finances.data[key].id) !== -1) {
            amount += parseInt(this.finances.data[key].jpy)
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
    EventItem,
    Loading
	}
}
</script>
