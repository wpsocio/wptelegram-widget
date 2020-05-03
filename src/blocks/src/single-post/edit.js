import { __ } from '@wordpress/i18n';
import { FocusableIframe, Spinner } from '@wordpress/components';
import { Component, Fragment, createRef } from '@wordpress/element';
import { addQueryArgs } from '@wordpress/url';
import { blocks } from 'plugin-data';

import BlockPlaceholder from './placeholder';
import AllControls from './controls';

const { message_view_url } = blocks?.assets;

class Edit extends Component {
	constructor() {
		super(...arguments);
		this.iframe_ref = createRef();

		this.switchBackToURLInput = this.switchBackToURLInput.bind(this);
		this.getIframeSrc = this.getIframeSrc.bind(this);
		this.toggleUserPic = this.toggleUserPic.bind(this);
		this.resizeIframe = this.resizeIframe.bind(this);
		this.setUrl = this.setUrl.bind(this);

		this.state = {
			loading: true,
			editingURL: false,
			error: false,
			url: this.props.attributes.url,
			userpic: this.props.attributes.userpic,
			iframe_height: null,
		};
	}

	toggleUserPic() {
		const userpic = !this.state.userpic;
		const loading = true;
		let { iframe_src } = this.props.attributes;

		iframe_src = addQueryArgs(iframe_src, { userpic });

		this.setState({ userpic, loading });
		this.props.setAttributes({ userpic, iframe_src });
	}

	setUrl(event) {
		if (event) {
			event.preventDefault();
		}
		const { url } = this.state;

		const regex = /^(?:https?:\/\/)?t\.me\/(?<username>[a-z][a-z0-9_]{3,30}[a-z0-9])\/(?<message_id>\d+)$/i;
		const match = url.match(regex);
		// validate URL
		if (null === match) {
			this.setState({ error: true });
		} else {
			const iframe_src = this.getIframeSrc(match.groups);
			const { setAttributes } = this.props;

			this.setState({ loading: true, editingURL: false, error: false });
			setAttributes({ url, iframe_src });
		}
	}

	getIframeSrc(data) {
		return message_view_url
			.replace('%username%', data.username)
			.replace('%message_id%', data.message_id)
			.replace('%userpic%', this.state.userpic);
	}

	switchBackToURLInput() {
		this.setState({ editingURL: true });
	}

	componentDidMount() {
		window.addEventListener('resize', this.resizeIframe);
	}

	componentWillUnmount() {
		window.removeEventListener('resize', this.resizeIframe);
	}

	resizeIframe() {
		if (
			null === this.iframe_ref.current ||
			'undefined' === typeof this.iframe_ref.current.contentWindow
		) {
			return;
		}
		const iframe_height = this.iframe_ref.current.contentWindow.document
			.body.scrollHeight;
		if (iframe_height !== this.state.iframe_height) {
			this.setState({ iframe_height });
		}
	}

	render() {
		const { loading, editingURL, url, error, userpic } = this.state;
		const { className, setAttributes } = this.props;
		const { alignment, iframe_src } = this.props.attributes;

		const label = __('Telegram post URL');

		if (editingURL || !iframe_src) {
			return (
				<BlockPlaceholder
					label={label}
					error={error}
					url={url}
					onChangeURL={(event) =>
						this.setState({ url: event.target.value })
					}
					onSubmit={this.setUrl}
				/>
			);
		}

		const iframe_height = loading ? 0 : this.state.iframe_height;

		return (
			<Fragment>
				<AllControls
					userpic={userpic}
					toggleUserPic={this.toggleUserPic}
					showEditButton={true}
					switchBackToURLInput={this.switchBackToURLInput}
					alignment={alignment}
					changeAlignment={(align) => {
						this.resizeIframe();
						setAttributes({ alignment: align });
					}}
				/>
				{loading && (
					<div className="wp-block-embed is-loading">
						<Spinner />
						<p>{__('Loading…')}</p>
					</div>
				)}

				<div className={className + ' wptelegram-widget-message'}>
					<div className={'wp-block-embed__content-wrapper'}>
						<FocusableIframe
							iframeRef={this.iframe_ref}
							frameBorder="0"
							scrolling="no"
							src={iframe_src}
							onLoad={() => {
								this.setState({ loading: false });
								this.resizeIframe();
							}}
							height={iframe_height}
						>
							Your Browser Does Not Support iframes!
						</FocusableIframe>
					</div>
				</div>
			</Fragment>
		);
	}
}

export default Edit;
