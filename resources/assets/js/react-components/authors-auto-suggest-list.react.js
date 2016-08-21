var AuthorsList = React.createFactory(React.createClass({
	getInitialState:function() {
		return {
			selectedID: -1
		}
	},
	onSelectAuthor: function(e) {
		//this.setState.selectedID = e.currentTarget.dataset.id;
		ReactDOM.findDOMNode(this.refs.id).value = e.currentTarget.dataset.id;
		$('#book-author').val(e.currentTarget.dataset.author);
		this.props.scope.$parent.toggleAuthorsSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	closeSuggestionBox: function() {
		this.props.scope.$parent.toggleAuthorsSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	render: function() {
		var keyword = (this.props.keyword ? this.props.keyword : '');
		var onSelectAuthor = this.onSelectAuthor;

		var _data = this.props.list.map(function(o){
			if(keyword !== '') {
				if(o.author_name.toLowerCase().match(keyword.toLowerCase())) {
					return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id, 'data-author':o.author_name, 'onClick': onSelectAuthor}, o.author_name);
				}
			}
			else {
				return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id,'data-author':o.author_name, 'onClick': onSelectAuthor}, o.author_name);
			}
		});
		return  React.DOM.div({className: 'author-suggest'},
			React.DOM.div(
				{className: 'autosuggest-author-body'},
				React.DOM.div(
					{className: "list-group"},
					_data, /*(this.state.showSuggestion ? _data : null),*/
					React.DOM.input({name: 'author_id', type: 'hidden', ref: 'id', value: this.state.selectedID})
				),
				React.DOM.a({className: 'list-group-item close-box-suggestion text-danger', 'onClick': this.closeSuggestionBox }, 'Close')
			)
		);
	}
}));