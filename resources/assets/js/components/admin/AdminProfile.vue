<template>


    <form id="update-user" class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

        <div class="form-body">

            <div class="form-group" :class="{'has-danger':form.errors.has('first_name')}">
                <label class="col-md-12">First Name</label>
                <div class="col-md-12">
                    <input type="text" class="form-control" name="name" v-model="form.first_name" required>
                    <small class="form-control-feedback" v-if="form.errors.has('first_name')"> <strong v-text="form.errors.get('first_name')"></strong></small>
                </div>
            </div>
            <div class="form-group" :class="{'has-danger':form.errors.has('last_name')}">
                <label class="col-md-12">Last Name</label>
                <div class="col-md-12">
                    <input type="text" class="form-control" name="name" v-model="form.last_name" required>
                    <small class="form-control-feedback" v-if="form.errors.has('last_name')"> <strong v-text="form.errors.get('last_name')"></strong></small>
                </div>
            </div>
            <div class="form-group" :class="{'has-danger':form.errors.has('email')}">
                <label class="col-md-12">Email</label>
                <div class="col-md-12">
                    <input type="email" class="form-control" name="email" v-model="form.email" required>
                    <small class="form-control-feedback" v-if="form.errors.has('email')"> <strong v-text="form.errors.get('email')"></strong></small>
                </div>
            </div>

            <div class="form-group" :class="{'has-danger':form.errors.has('password')}">
                <label class="col-md-12">Password</label>
                <div class="col-md-12">
                    <input type="password" class="form-control" name="password" v-model="form.password" >
                    <small class="form-control-feedback" v-if="form.errors.has('password')"> <strong v-text="form.errors.get('password')"></strong></small>
                </div>
            </div>

            <div class="form-group" :class="{'has-danger':form.errors.has('password_confirmation')}">
                <label class="col-md-12">Password Confirm</label>
                <div class="col-md-12">
                    <input type="password" class="form-control" name="password_confirmation" v-model="form.password_confirmation" >
                    <small class="form-control-feedback" v-if="form.errors.has('password_confirmation')"> <strong v-text="form.errors.get('password_confirmation')"></strong></small>
                </div>
            </div>


        </div>
        <hr>
        <div class="form-actions" style="padding-left: 15px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-success" :disabled="form.errors.any()">Update</button>

                        </div>
                    </div>
                </div>

            </div>
        </div>



    </form>



</template>
<script>




    export default{


        data(){

            return {


                form:new Form({first_name:'',last_name:'',email:'',password:'',password_confirmation:''}),
                user_id:this.id

            };
        },
        props:['id'],
        mounted(){


            axios.get('/admin/get-user/'+this.user_id).
            then(response=>{


                this.form.first_name=response.data.first_name;
                this.form.last_name=response.data.last_name;
                this.form.email=response.data.email;

            });



        },
        methods:{


            onSubmit(){


                this.form.patch('/admin/update-profile/'+this.user_id,false)
                    .then(status=>{



                        if(status.success){

                            $.toast({
                                heading: 'Profile',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Profile',
                                text: 'Error editing  profile!',
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }


                    });

            }

        }

    }

</script>