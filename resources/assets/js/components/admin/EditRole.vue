<template>


    <form id="update-role" class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

        <h3>{{role_name | capitalize}} Permissions</h3>
        <hr>
        <div class="form-body">

            <div v-for="permission in form.permissions">
                <input type="checkbox"  :value="permission.id" :id="permission.id"  :checked="permission.checked" @change="check(permission.id)">
                <label :for="permission.id">{{permission.name}}</label>
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


                form:new Form({permissions:[]}),
                role_name:''

            };
        },
        props:['id'],
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        mounted(){


            axios.get('/admin/get-role-permissions/'+this.id).
            then(response=>{


                this.form.permissions=response.data;
                this.role_name=this.form.permissions[0].role_name;


            });



        },
        methods:{


            check(id){

                for(let i=0;i<this.form.permissions.length;i++){

                    if(this.form.permissions[i].id==id){
                        this.form.permissions[i].checked=!this.form.permissions[i].checked;
                    }

                }

            },
            onSubmit(){


                this.form.patch('/admin/update-permissions/'+this.id,false)
                    .then(status=>{



                        if(status.success){

                            $.toast({
                                heading: 'Permissions',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Permissions',
                                text: 'Error editing  Permissions!',
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