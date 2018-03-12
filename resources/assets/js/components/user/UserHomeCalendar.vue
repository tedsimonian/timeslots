<template>


    <div class="row">


        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12">
                            <h3>Appointment Legends</h3>

                        </div>
                        <hr>
                        <div class="col-md-3">
                            <span class="completed legend-small"></span><p>Completed</p>
                        </div>

                        <div class="col-md-3">
                            <span class="pending legend-small"></span><p>Pending</p>
                        </div>
                        <div class="col-md-3">
                            <span class="failed legend-small"></span><p>Failed</p>
                        </div>
                        <div class="col-md-3">

                            <span class="selected legend"></span><p>Selected Day</p>

                        </div>


                    </div>
                    <hr>
                    <v-date-picker
                            @input="selectDate"
                            :attributes='attributes'
                            is-inline
                            is-expanded
                            is-double-paned
                            mode='single'
                            v-model='selectedDate'
                            show-caps>

                    </v-date-picker>
                    <hr>

                    <div class="row" v-if="pending_events.length>0">

                        <div class="col-md-12">

                            <h3>Pending Appointments</h3>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Price</th>
                                    <th>Employee</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item,index) in pending_events">
                                    <td>{{item.day}}</td>
                                    <td>
                                        {{item.timeslot}} - {{endTime(item.timeslot,item.duration)}}
                                    </td>
                                    <td>
                                        ${{item.price}}
                                    </td>
                                    <td>
                                        {{item.employee.first_name}} {{item.employee.last_name}}
                                    </td>
                                    <td>
                                        <span class="status" :class="item.status">{{item.status}}</span>
                                        <button style="width: 100%;margin-top: 10px;" class="btn btn-info" @click="checkout(item.price,item.id)">Pay</button>

                                    </td>

                                </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                    <div class="row" v-else >

                        <div class="col-md-12">

                            <p>There are no pending appointments.</p>
                        </div>

                    </div>

                </div>

            </div>



        </div>

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="selected-date">
                        <h3>Selected Date:{{selectedDateFormated}}</h3>
                        <hr>
                        <div class="row" v-if="events.length>0">

                            <div class="col-md-12">

                                <h3>Appointments</h3>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Price</th>
                                        <th>Employee</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item,index) in events">
                                        <td>{{item.day}}</td>
                                        <td>
                                            {{item.timeslot}} - {{endTime(item.timeslot,item.duration)}}
                                        </td>
                                        <td>
                                            ${{item.price}}
                                        </td>
                                        <td>
                                            {{item.employee.first_name}} {{item.employee.last_name}}
                                        </td>
                                        <td>
                                            <span class="status" :class="item.status">{{item.status}}</span>
                                            <button style="width: 100%;margin-top: 10px;" v-if="item.status=='pending'" class="btn btn-info" @click="checkout(item.price,item.id)">Pay</button>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                        <div class="row" v-else >

                            <div class="col-md-12">

                                <p>There are no appointments at this date.</p>
                            </div>

                        </div>


                    </div>

                </div>

            </div>







        </div>






    </div>






</template>
<script>

    import VCalendar from 'v-calendar';
    import 'v-calendar/lib/v-calendar.min.css';
    import moment from 'moment';
    import VueStripeCheckout from 'vue-stripe-checkout';


    const options = {
        name: 'Payment Process',
        key:'pk_test_a1ce7hnnL7ysRshCjsgjY26J',
        currency: 'CAD',
        image: 'https://blog.apruve.com/hs-fs/hubfs/Payment%20Delays-01.png?width=100&name=Payment%20Delays-01.png',
        locale: 'auto',
        billingAddress: false,
        panelLabel: 'Pay Now {{amount}}',
    };

    Vue.use(VueStripeCheckout,options);

    export default{


        data(){
            return {

                selectedDate: new Date(),
                events_marker:[],
                events:[],
                pending_events:[],
                can:true

            };
        },
        components: {VCalendar},
        computed:{

            selectedDateFormated(){

                return moment(this.selectedDate).format("MMMM Do YYYY");
            },
            attributes() {


                let attributes=[];
                for(let i=0;i<this.events_marker.length;i++){
                    attributes.push({

                        dot: {
                            backgroundColor: this.events_marker[i].backgroundColor,
                        },
                        popover:{

                            label:this.events_marker[i].count+ ' Appointment(s)',
                        },
                        dates: this.events_marker[i].date
                    });

                }

                return attributes;

            },

        },
        mounted(){




            this.getEvents();
            this.getEventsByDay(new Date());
            this.getPendingEvents();

        },
        methods:{

            checkout(amount,event_id) {

                this.$checkout.open({
                    amount: amount*100,
                    token: (token) => {

                        axios.post('/user/complete-event',{

                            pay_later:true,
                            amount:amount*100,
                            token:token,
                            event_id:event_id}).
                        then(response=>{



                            if(response.data.success){


                                $.toast({
                                    heading: 'Appointment',
                                    text: response.data.message,
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 1000,
                                    stack: 6
                                });

                                this.getPendingEvents();
                                this.getEvents();
                                this.getEventsByDay(this.selectedDate);


                            }else{

                                $.toast({
                                    heading: 'Appointment',
                                    text: 'Error booking an Appointment!',
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 1000,
                                    stack: 6
                                });
                            }



                        });
                    }
                });
            },
            endTime(time,duration){

                let numbers=time.split(':');
                let hour=parseInt(numbers[0]);
                let minutes=parseInt(numbers[1]);

                let total=((hour*60)+minutes)+parseInt(duration);
                let final_hours = Math.trunc(total/60);
                let final_minutes = (total % 60)==0 ? '00' : total % 60;




                return  final_hours + ':' + final_minutes;
            },
            selectDate(date){


                if(this.selectedDate==null){

                    this.selectedDate=new Date();

                }
                this.getEventsByDay(date);


            },
            getEventsByDay(day){

                let new_date=moment(day).format('YYYY-MM-DD');
                axios.get('/user/get-events-day?day='+new_date).
                then(response=>{



                    this.events=response.data;

                });
            },
            getEvents(){


                axios.get('/user/get-events').
                then(response=>{



                   this.events_marker=response.data;

                });
            },
            getPendingEvents(){


                axios.get('/user/get-pending-events').
                then(response=>{



                    this.pending_events=response.data;

                });
            }
        }


    }


</script>
<style>

    .selected-date{

        border:1px solid rgb(218, 218, 218);
        padding:10px;
    }

    .status{

        display: block;
        width: 100%;
        text-align:center;
        border-radius: 3px;
        padding: 3px;
        color:white;
    }

    .completed{

        background-color:green;
    }

    .pending{

        background-color:rgb(255, 178, 43)
    }

    .failed{

        background-color:red;
    }

    .legend{

        float:left;
        margin-right:15px;
        display: block;
        height: 25px;
        width:25px;
        border-radius: 50%;

    }

    .legend-small{

        margin-top: 5px;
        float:left;
        margin-right:15px;
        display: block;
        height: 15px;
        width:15px;
        border-radius: 50%;

    }

    .selected{

        background: rgb(102, 179, 204);
    }


</style>
