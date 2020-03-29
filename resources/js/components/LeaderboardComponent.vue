<template>
    <div class="center">
        <!--<div class="top3">
            <div class="two item">
                <div class="pos">
                    <img src="/images/leaderboard/second.png" alt="">
                </div>
                <div class="pic" :style="secondPos['head_img']"></div>
                <div class="name">
                    {{secondPos["true_name"]}}
                </div>
                <div class="today_score">
                    今日积分：{{secondPos["today_score"]}}
                </div>
                <div class="score">
                    总分：{{secondPos["total_score"]}}
                </div>
            </div>
            <div class="one item">
                <div class="pos">
                    <img src="/images/leaderboard/first.png" alt="">
                </div>
                <div class="pic" :style="firstPos['head_img']"></div>
                <div class="name">
                    {{firstPos["true_name"]}}
                </div>
                <div class="today_score">
                    今日积分：{{firstPos["today_score"]}}
                </div>
                <div class="score">
                    总分：{{firstPos["total_score"]}}
                </div>
            </div>
            <div class="three item">
                <div class="pos">
                    <img src="/images/leaderboard/third.png" alt="">
                </div>
                <div class="pic" :style="thirdPos['head_img']"></div>
                <div class="name">
                    {{thirdPos["true_name"]}}
                </div>
                <div class="today_score">
                    今日积分：{{thirdPos["today_score"]}}
                </div>
                <div class="score">
                    总分：{{thirdPos["total_score"]}}
                </div>
            </div>
        </div>-->

        <div class="logo">
            <img src="/images/leaderboard/logo1.png" alt="">
        </div>
        <div class="list">
            <div class="item" v-for="(pos, index) in otherPos">
                <div class="pos">
                    {{index+4}}
                </div>
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
                <div class="badge">
                    <img src="/images/leaderboard/badge_5.jpg" alt="">
                </div>
            </div>
        </div>

        <div id="leaderboard_update_time-div">
            <span>积分榜更新时间：{{leaderboard_update_time}}</span>
        </div>

        <div class="duty">
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
                firstPos: [],
                secondPos: [],
                thirdPos: [],
                otherPos: [],
                dutyStaffData: [],
                leaderboard_update_time: '',
                today_duty_update_time: ''
            }
        },
        methods: {
            getLeaderboard() {
                let leaderboardUrl = "/api/getLeaderboard";
                this.axios.get(leaderboardUrl).then(response => {
                    if (response.status == 200) {
                        this.leaderboard = response.data;
                        this.firstPos = response.data[0];
                        this.secondPos = response.data[1];
                        this.thirdPos = response.data[2];

                        let otherPos = [];
                        for (let i=0; i<response.data.length; i++) {
                            if (i <= 2) {
                                continue;
                            }
                            otherPos.push(response.data[i]);
                        }
                        this.otherPos = otherPos;

                        let myDate = new Date();
                        this.leaderboard_update_time = myDate.getFullYear() + '-' + (myDate.getMonth()+1) + '-' + myDate.getDate() + ' ' + myDate.getHours() + ':' + myDate.getMinutes();
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
                    } else {
                        console.log('获取值日数据失败')
                    }
                });
            }
        },
        mounted: function() {
            this.getLeaderboard();
            this.getTodayDuty();
            setInterval(this.getLeaderboard, 60000);
        }
    }
</script>