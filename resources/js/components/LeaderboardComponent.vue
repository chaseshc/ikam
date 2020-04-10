<template>
    <div class="center">
        <div class="logo">
            <img src="/images/leaderboard/logo1.png" alt="">
        </div>
        <div class="list">
            <div class="item-list">
                <div class="item" v-for="(pos, index) in leaderboard_data">
                    <div class="pos" v-if="index === 0">
                        <img src="/images/leaderboard/first.png" alt="">
                    </div>
                    <div class="pos" v-else-if="index === 1">
                        <img src="/images/leaderboard/second.png" alt="">
                    </div>
                    <div class="pos" v-else-if="index === 2">
                        <img src="/images/leaderboard/third.png" alt="">
                    </div>
                    <div class="pos" v-else>
                        {{index+1}}
                    </div>

                    <div class="one-item">
                        <div class="pic" :style="pos['head_img']"></div>
                        <div class="name">
                            {{pos["true_name"]}}
                        </div>
                        <div class="today_score">
                            {{pos["today_score"]}}
                        </div>
                        <div class="score">
                            {{pos["total_score"]}}
                        </div>
                        <div class="badge" v-if="pos['reward_icon'] != ''">
                            <img v-bind:src="pos['reward_icon']" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div id="leaderboard_update_time-div" v-show="is_show">
                <span>积分榜更新时间：{{leaderboard_update_time}}</span>
            </div>
        </div>

        <div class="duty" v-show="is_show">
            <div class="item">
                <div class="title">
                    今日值日
                </div>
                <div class="name">
                    {{dutyStaffData['true_name']}}
                </div>
                <div class="update_time">
                    更新时间：{{today_duty_update_time}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                leaderboard_data: [],
                leaderboard_update_time: '',
                dutyStaffData: [],
                today_duty_update_time: '',
                is_show:false
            }
        },
        methods: {
            getLeaderboard() {
                let leaderboardUrl = "/api/getLeaderboard";
                this.axios.get(leaderboardUrl).then(response => {
                    if (response.status == 200) {
                        this.leaderboard_data = response.data.leaderboard_data;
                        this.leaderboard_update_time = response.data.leaderboard_update_time;
                        this.is_show = true;
                    } else {
                        console.log('获取积分榜数据失败')
                    }
                });
            },

            getTodayDuty() {
                let dutyUrl = "/api/getTodayDuty";
                this.axios.get(dutyUrl).then(response => {
                    if (response.status == 200) {
                        this.dutyStaffData = response.data.today_duty_staff;
                        this.today_duty_update_time = response.data.duty_created_at;
                        this.is_show = true;
                    } else {
                        console.log('获取值日数据失败')
                    }
                });
            }
        },
        mounted: function() {
            this.getLeaderboard();
            this.getTodayDuty();
            setInterval(this.getLeaderboard, 600000);
        }
    }
</script>