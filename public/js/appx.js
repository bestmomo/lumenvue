var token = $('#token').val();

new Vue({
	el: '#page-top',
	data: {
		dreams: {},
		isLogged: false,
		page: 1,
		previous: false,
		next: false,
        errorEmail: null,
        errorPassword: null,
        formData: {
        	email: null,
        	password: null,
        	memory: false,
        	_token: token
        },
        createData: {
        	content: null,
        	_token: token
        },
        updateData: {
        	content: null,
        	_token: token
        },
        id: null,
        index: null,
        isAlert: false,
        resource: null,
        errorCreateContent: null,
        errorContent: null
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
                --this.page;
            }
            else if (direction === 'next') {
                ++this.page;
            };
			this.resource.get({page: this.page}, function (data) {
                this.dreams = data.data;
                this.previous = data.prev_page_url;
                this.next = data.next_page_url;
			}).error(function (data) {
				console.log("Error:" + JSON.stringify(data));
			});
        },
        login: function (e) {
            e.preventDefault();
            this.errorEmail = null;
            this.errorPassword = null;
            this.isAlert = false;
            this.$http.post('auth/login', this.formData, function (data) {
                if (data.result === 'success') {
                    this.isLogged = true;
                    this.paginate();
                    window.location = '#page-top';
                } else {
                    this.isAlert = true;
                };
            }).error(function (data) {
				console.log("Error:" + JSON.stringify(data));
			});
		},           
		logout: function() {
			this.$http.get('auth/logout', function () {
				this.isLogged = false;
				$.each(this.dreams, function(key) {
					this.dreams[key].is_owner = false;
				});
			}).error(function (data) {
				console.log("Error:" + JSON.stringify(data));
			});
		},
		create: function (e) {
			e.preventDefault();
            this.errorCreateContent = null;
			this.resource.save(this.createData, function (data) {
                this.createData.content = null;
                this.page = 1;
                this.dreams = data.data;
                this.previous = data.prev_page_url;
                this.next = data.next_page_url;
                window.location = '#dreams';
			}).error(function (data) {
				this.errorCreateContent = data.content[0];
			});
		},
		destroy: function (id) {
            if (confirm("Really delete this dream ?")) {
				this.resource.remove({id: id, _token: token}, function (data) {
	                this.paginate();
				}).error(function (data) {
					console.log("Error:" + JSON.stringify(errorResponse));
				});
            }
		},
		edit: function (id, index) {
            this.errorContent = null;
            this.id = this.dreams[index].id;
            this.updateData.content = this.dreams[index].content;
            this.index = index;
            $('#myModal').modal();			
		},
		update: function(e) {
			e.preventDefault();
            this.errorContent = null;
			this.$http.put('dream/' + this.id, this.updateData, function (data) {
                this.dreams[this.index].content = this.updateData.content;
                $('#myModal').modal('hide');
			}).error(function (data) {
				this.errorContent = data.content[0];
			});			
		}
	}
});
