import React, { Component } from 'react';
import Paper from 'material-ui/Paper';
import Subheader from 'material-ui/Subheader';
import { GridList, GridTile } from 'material-ui/GridList';
import IconButton from 'material-ui/IconButton';
import FeedIcon from 'material-ui/svg-icons/communication/rss-feed';
import CommentIcon from 'material-ui/svg-icons/communication/forum';
import request, { createRequestURI } from '../utils/request';

class HomeComponent extends Component {

  state = {
    feedsCount: '加载中...',
    commentsCount: '加载中...',
  };

  render() {
    const { feedsCount, commentsCount } = this.state;

    return (
      <div>
        <Paper
          style={{
            padding: 12
          }}
          zDepth={1}
        >
          分享动态管理
        </Paper>

        <Subheader>动态统计</Subheader>
        <GridList>
          <GridTile
            title="动态数"
            subtitle={feedsCount}
            actionIcon={<IconButton><FeedIcon color="white" /></IconButton>}
            actionPosition="right"
            style={{
              background: '#E91E63'
            }}
          />
          <GridTile
            title="评论数"
            subtitle={commentsCount}
            actionIcon={<IconButton><CommentIcon color="white" /></IconButton>}
            actionPosition="right"
            style={{
              background: '#4DB6AC'
            }}
          />
        </GridList>

      </div>
    );
  }

  componentDidMount() {
    request.get(
      createRequestURI('statistics'),
      { validateStatus: status => status === 200 }
    ).then(({ data: { feedsCount = '加载失败', commentsCount = '加载失败' } }) => {
      this.setState({ ...this.state, feedsCount, commentsCount });
    }).catch();
  }

}

export default HomeComponent;
