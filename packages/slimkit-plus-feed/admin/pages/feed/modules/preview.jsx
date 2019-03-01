import React from 'react';
import { localDate } from "../../../utils/dateProcess";
import Modal from '@material-ui/core/Modal';
import Paper from '@material-ui/core/Paper';
import Card from '@material-ui/core/Card';
import CardHeader from '@material-ui/core/CardHeader';
import CardMedia from '@material-ui/core/CardMedia';
import CardContent from '@material-ui/core/CardContent';
import CardActions from '@material-ui/core/CardActions';
import Avatar from '@material-ui/core/Avatar';
import IconButton from '@material-ui/core/IconButton';
import Typography from '@material-ui/core/Typography';
import GridList from '@material-ui/core/GridList';
import GridListTile from '@material-ui/core/GridListTile';
import GridListTileBar from '@material-ui/core/GridListTileBar';
import { createRequestURI } from '../../../utils/request';

// icons
import CloseIcon from '@material-ui/icons/Close';
import VisibilityIcon from '@material-ui/icons/Visibility';
import FavoriteIcon from '@material-ui/icons/Favorite';

function RenderUserAvatar(props) {
    let { name, avatar } = props.user;

    if (avatar) {
        return (<Avatar src={avatar.url} />);
    }

    return <Avatar>{ name[0] }</Avatar>;
}

export default function Preview(props) {
    let { feed, onClose, classes } = props;

    if (! feed) {
        return null;
    }

    let { user, video = null, images = [], topics = [] } = feed;

    return (
        <Modal
            open={true}
            onClose={onClose}
        >
            <Paper className={classes.reviewWrap}>
                <Card>
                    <CardHeader
                        avatar={<RenderUserAvatar user={user} />}
                        action={<IconButton
                            onClick={onClose}
                        >
                            <CloseIcon />
                        </IconButton>}
                        title={user.name}
                        subheader={localDate(feed.created_at)}
                    />

                    {/* 视频 */}
                    {video ? (
                        <CardMedia
                            className={classes.previewVideo}
                            component="video"
                            controls={true}
                            poster={createRequestURI(`../../../api/v2/files/${video.cover_id}`)}
                            src={createRequestURI(`../../../api/v2/files/${video.video_id}`)}
                        />
                    ) : null}

                    {/* 文本 */}
                    <CardContent>
                        <Typography component="p">{ feed.feed_content }</Typography>
                    </CardContent>

                    {/* 图片 */}
                    {images ? (
                        <div className={classes.imageRoot}>
                            <GridList cols={images.length > 3 ? 2.5 : images.length} className={classes.imageGridList}>
                                {images.map(image => (
                                    <GridListTile key={image.id}>
                                        <img src={createRequestURI(`../../../api/v2/files/${image.id}`)} />
                                        {image.paid_node ? (
                                            <GridListTileBar
                                                title={`${image.paid_node.extra === 'read' ? '查看' : '下载'}收费`}
                                                subtitle={`需要${image.paid_node.amount/100}积分`}
                                            />
                                        ) : null}
                                    </GridListTile>
                                ))}
                            </GridList>
                        </div>
                    ) : null}

                    {/* 话题 */}
                    {topics.length ? (
                        <CardContent>
                            {topics.map(topic => (
                                <Chip
                                    key={topic.id}
                                    label={topic.name}
                                    className={classes.actionsFab}
                                />
                            ))}
                        </CardContent>
                    ) : null}

                    {/* 状态栏 */}
                    <CardActions disableActionSpacing className={classes.actions}>
                        {/* 喜欢数 */}
                        <IconButton disabled>
                            <FavoriteIcon />
                        </IconButton>
                        { feed.like_count }

                        {/* 阅读数 */}
                        <IconButton disabled>
                            <VisibilityIcon />
                        </IconButton>
                        { feed.feed_view_count }

                    </CardActions>

                </Card>
            </Paper>
        </Modal>
    );
}
