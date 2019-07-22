import React from 'react';
import { Card, ListGroup } from 'react-bootstrap';
import { __, sprintf } from '../i18n';
import Code from './Code';

export default () => {
  const { wptelegram_widget: { settings: { assets: { admin_url } } } } = window;

  return (
    <Card className="mw-100 mt-0 p-0 text-center border-top-1 border-right-0 border-left-0 border-bottom-0">
      <Card.Header as="h6" className="text-center">{__('Widget Info')}</Card.Header>
      <ListGroup variant="flush">
        <ListGroup.Item className="text-justify" dangerouslySetInnerHTML={{__html:sprintf( __( 'Goto %1$s and click/drag %2$s and place it where you want it to be.'), `<b>${__( 'Appearance' )}</b> &gt; <a href="${admin_url}/widgets.php">${__( 'Widgets' )}</a>`, `<b>${__( 'WP Telegram Ajax Widget' )}</b>` )}}>
        </ListGroup.Item>
        <ListGroup.Item className="text-justify">
          {__( 'Alternately, you can use the below shortCode or the block available in block editor.')}
        </ListGroup.Item>
        <ListGroup.Item className="font-weight-bold text-secondary">
          {__( 'Inside page or post content:')}
        </ListGroup.Item>
        <ListGroup.Item variant="light" className="text-monospace text-left">
          <Code>[wptelegram-ajax-widget widget_width="100%" widget_height="500"]</Code>
        </ListGroup.Item>
        <ListGroup.Item className="font-weight-bold text-secondary">
          {__( 'Inside the theme templates')}
        </ListGroup.Item>
        <ListGroup.Item variant="light" className="text-monospace text-left">
          <Code>{`<?php\nif ( function_exists( 'wptelegram_ajax_widget' ) ) {\n    wptelegram_ajax_widget();\n}\n?>`}</Code>
          <br />
          <span className="font-weight-bold text-secondary">{__('or')}</span>
          <br />
          <Code>{`<?php\necho do_shortCode( \'[wptelegram-ajax-widget widget_width="98%" widget_height="700"]\' );\n?>`}</Code>
        </ListGroup.Item>
      </ListGroup>
    </Card>
  );
}
