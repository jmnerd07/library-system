var PublishersList = React.createFactory(React.createClass({
	getInitialState:function() {
		return {
			selectedID: -1
		}
	},
	onSelectPublisher: function(e) {
		//this.setState.selectedID = e.currentTarget.dataset.id;
		ReactDOM.findDOMNode(this.refs.id).value = e.currentTarget.dataset.id;
		$('#book-publisher').val(e.currentTarget.dataset.publisher);
		this.props.scope.$parent.togglePublisherSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	closeSuggestionBox: function() {
		this.props.scope.$parent.togglePublisherSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	render: function() {
		var keyword = (this.props.keyword ? this.props.keyword : '');
		var onSelectPublisher = this.onSelectPublisher;

		var _data = this.props.list.map(function(o){
			if(keyword !== '') {
				if(o.name.toLowerCase().match(keyword.toLowerCase())) {
					return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id, 'data-author':o.name, 'onClick': onSelectPublisher}, o.name);
				}
			}
			else {
				return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id,'data-author':o.name, 'onClick': onSelectPublisher}, o.name);
			}
		});
		return  React.DOM.div({className: 'publisher-suggest'},
		React.DOM.div(
		{className: 'autosuggest-publisher-body'},
		React.DOM.div(
		{className: "list-group"},
		_data, /*(this.state.showSuggestion ? _data : null),*/
		React.DOM.input({name: 'publisher_id', type: 'hidden', ref: 'id', value: this.state.selectedID})
		),
		React.DOM.a({className: 'list-group-item close-box-suggestion text-danger', 'onClick': this.closeSuggestionBox }, 'Close')
		)
		);
	}
}));