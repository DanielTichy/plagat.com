JA_NewsHeadline = new Class ({
	initialize: function(options){
		this.options = Object.extend({
			autoroll: 0,
			total: 0,
			delaytime: 10
		}, options || {});
		this.elements = [];
		this._next = 0;
	},

	start: function() {
		//Get cache news to array
		//if(!$('ja-newshlcache') || !$('ja-newshlcache').getChildren()) return;
		this.container = $('jahl-newsitem');
		this.container.setStyles ({
			overflow: 'hidden',
			display: 'block',
			position: 'relative'
		});
		$('ja-newshlcache').getChildren().each(function (el){
			el._title = el.getElement('.ja-newstitle')?el.getElement('.ja-newstitle').getProperty('title'):'';
			el.setStyles({
				opacity: 0,
				display: 'block',
				width: this.container.offsetWidth,
				position: 'absolute',
				top: 0,
				left: 0
			});
			el.remove().inject (this.container);
			this.elements.push(el);
		},this);
		this.animfirst();
	},
	
	run: function() {
		if(!this.options.autoroll || this.options.total<2) return;
		this._next = this._current < this.options.total - 1?this._current+1:0;
		this.timer = setTimeout(this.swap.bind(this), this.options.delaytime*1000);	
	},
	
	getNext: function() {
		return (this._current < this.options.total - 1)?this._current+1:0;
	},

	getPrev: function() {
		return this._current > 0 ? this._current - 1 : this.options.total - 1;
	},

	next: function() {
		this._next = this.getNext();
		this.swap();
	},
	
	prev: function() {
		this._next = this.getPrev();
		this.swap();
	},
	
	toogle: function() {
		clearTimeout(this.timer);
		this.options.autoroll = this.options.autoroll?0:1;
		Cookie.set('JAHL-AUTOROLL',this.options.autoroll);
		if($('jahl-switcher')) {
			$('jahl-switcher').src = this.options.autoroll? $('jahl-switcher').src.replace('play','pause'):$('jahl-switcher').src.replace('pause','play');
			$('jahl-switcher').title = this.options.autoroll?'Pause':'Play';
		}
		this.run();
	},

	swap: function() {
		if(!this.elements.length) return;
		clearTimeout(this.timer);
		this.animrun();
	},

	animfirst: function (){
		this._current = 0;
		var el2 = this.elements[this._current];
		new Fx.Style(el2, 'opacity').start(0, 1);
		new Fx.Style(this.container, 'height').start(0, el2.offsetHeight);

		if($('jahl-prev')) $('jahl-prev').setProperty('title', this.elements[this.getPrev()]._title);
		if($('jahl-next')) $('jahl-next').setProperty('title', this.elements[this.getNext()]._title);
		if($('jahl-indicator')) $('jahl-indicator').innerHTML = (this._current+1)+"/"+this.options.total;
		this.run();
	},
	
	animrun: function() {
		var el1 = this.elements[this._current];
		this._current = this._next;
		var el2 = this.elements[this._current];
		new Fx.Style(el1, 'opacity').start(1, 0);
		new Fx.Style(el2, 'opacity').start(0, 1);
		new Fx.Style(this.container, 'height').start(el1.offsetHeight, el2.offsetHeight);

		if($('jahl-prev')) $('jahl-prev').setProperty('title', this.elements[this.getPrev()]._title);
		if($('jahl-next')) $('jahl-next').setProperty('title', this.elements[this.getNext()]._title);
		if($('jahl-indicator')) $('jahl-indicator').innerHTML = (this._current+1)+"/"+this.options.total;
		this.run();
	}
});