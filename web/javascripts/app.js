var Phonebook = Phonebook || {};
Phonebook.Views = {};

Phonebook.Contact = Backbone.Model.extend({
    getFullName: function() {
        return this.get('first_name') + ' ' + this.get('last_name');
    },

    addNumber: function(num) {
        var numbers = _.clone(this.get('numbers'));

        numbers.push(num);

        this.set({ numbers: numbers });
    },

    validate: function(attrs, options) {
        var errors = [];

        if (!attrs.first_name) {
            errors.push('Your contact needs a first name!');
        }
        if (!attrs.last_name) {
            errors.push('Your contact needs a last name!');
        }
        if (!attrs.numbers) {
            errors.push('Your contact needs a number');
        }

        if (errors.length > 0) {
            return errors.join(' ,');
        }
    }
});

Phonebook.Contacts = Backbone.Collection.extend({
    model: Phonebook.Contact,
    url: 'http://local.phonebook.com/contacts'
});

Phonebook.Views.Contacts = Backbone.View.extend({
    el: "#contact-app",

    events: {
        'submit #contact-add': 'onSubmit'
    },

    initialize: function () {
        this.listenTo(this.collection, 'add', this.addOne);
        this.listenTo(this.collection, 'reset', this.addAll);
        this.listenTo(this.collection, 'error', this.handleError);

        this.collection.fetch();
    },

    handleError: function(model, xhr) {
        var view = new Phonebook.Views.Error({
            type: 'alert',
            message: JSON.parse(xhr.response).error
        });

        this.$('#error-list').append(view.render().el);
    },

    addOne: function(contact) {
        var view = new Phonebook.Views.Contact({ model: contact });

        this.$('#contact-list').append(view.render().el);
    },

    addAll: function() {
        this.collection.each(this.addOne, this);
    },

    onSubmit: function(evt) {
        var data = this.serializeForm();

        evt.preventDefault();

        this.collection.create(data);

        this.resetForm();
    },

    serializeForm: function() {
        var values = {};

        this.$('#contact-add').find('input').each(function (idx, child) {
            $child = $(child);
            values[$child.attr('name')] = $child.val();
        });

        // hack while we don't input multiple numbers
        if (!_.isArray(values.numbers)) {
            values.numbers = [{ number: values.numbers }];
        }

        return values;
    },

    resetForm: function() {
        this.$('#contact-add').find('input').each(function (idx, child) {
            $(child).val('');
        });
    }
});

Phonebook.Views.Contact = Backbone.View.extend({
    tagName: 'div',
    className: 'row contact',
    template: _.template($('#contact-template').html()),

    events: {
        'click .delete': 'deleteModel'
    },

    initialize: function () {
        this.listenTo(this.model, 'change', this.render);
        this.listenTo(this.model, 'destroy', this.remove);
    },

    deleteModel: function() {
        if (confirm('Are you sure you want to delete ' + this.model.getFullName())) {
            this.model.destroy();
        }
    },

    render: function () {
        this.$el.html(this.template(this.model.toJSON()));

        return this;
    }
});

Phonebook.Views.Error = Backbone.View.extend({
    template: _.template($('#contact-error').html()),

    events: {
        'click .close': 'remove'
    },

    render: function () {
        this.$el.html(this.template(this.options));

        return this;
    }
});