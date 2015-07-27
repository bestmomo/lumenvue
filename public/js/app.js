Vue.http.headers.common['X-CSRF-TOKEN'] = $("meta[name=token]").attr("value");

new Vue({
    el: '#page-top',
    data: {
        dreams: {},
        isLogged: false,
        isAlert: false,
        pagination: {
            page: 1,
            previous: false,
            next: false          
        },
        loginData: {
            email: null,
            password: null,
            memory: false
        },
        createData: {
            content: null
        },
        updateData: {
            content: null
        },
        error: {
            email: null,
            password: null,  
            createContent: null,
            updateContent: null         
        },
        temp: {
            id: null,
            index: null           
        },
        resource: null
    },
    ready: function() {
        this.$http.get('auth/log', function (data) {
            this.isLogged = data.auth;
        }).error(function (data) {
            console.log("Error:" + JSON.stringify(data));
        });
        this.resource = this.$resource('dream/:id');
        this.paginate();
    },
    methods: {
        paginate: function (direction) {
            if (direction === 'previous') {
                --this.pagination.page;
            }
            else if (direction === 'next') {
                ++this.pagination.page;
            }
            this.resource.get({page: this.pagination.page}, function (data) {
                setData(this, data);
            }).error(function (data) {
                console.log("Error:" + JSON.stringify(data));
           });
        },
        login: function (e) {
            e.preventDefault();
            this.error.email = this.error.password = this.isAlert = false;
            this.$http.post('auth/login', this.loginData, function (data) {
                if (data.result === 'success') {
                    this.isLogged = true;
                    this.paginate();
                    window.location = '#page-top';
                } else {
                    this.isAlert = true;
                };
            }).error(function (data) {
                if (data.password) {
                    this.error.password = data.password[0];
                }
                if (data.email) {
                    this.error.email = data.email[0];
                }
            });
        },           
        logout: function() {
            this.$http.get('auth/logout', function () {
                $.each(this.dreams, function(key) {
                    this.dreams[key].is_owner = false;
                });
                this.isLogged = false;
            }).error(function (data) {
                console.log("Error:" + JSON.stringify(data));
            });
        },
        create: function (e) {
            e.preventDefault();
            this.error.createContent = null;
            this.resource.save(this.createData, function (data) {
                this.createData.content = null;
                this.pagination.page = 1;
                setData(this, data);
                window.location = '#dreams';
            }).error(function (data) {
                this.error.createContent = data.content[0];
            });
        },
        destroy: function (id) {
            if (confirm("Really delete this dream ?")) {
                this.resource.remove({id: id}, function () {
                    this.paginate();
                }).error(function (data) {
                    console.log("Error:" + JSON.stringify(data));
                });
            }
        },
        edit: function (id, index) {
            this.error.updateContent = null;
            this.temp.id = this.dreams[index].id;
            this.temp.index = index;
            this.updateData.content = this.dreams[index].content;
            $('#myModal').modal();            
        },
        update: function(e) {
            e.preventDefault();
            this.error.updateContent = null;
            this.$http.put('dream/' + this.temp.id, this.updateData, function (data) {
                this.dreams[this.temp.index].content = this.updateData.content;
                $('#myModal').modal('hide');
            }).error(function (data) {
                this.error.updateContent = data.content[0];
            });            
        }
    }
});

function setData (instance, data) {
    instance.dreams = data.data;
    instance.pagination.previous = data.prev_page_url;
    instance.pagination.next = data.next_page_url;    
}
