import lightBaseTheme from 'material-ui/styles/baseThemes/lightBaseTheme';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import {
  lightBlue500, lightBlue700
} from 'material-ui/styles/colors';

// 基于 lightBaseTheme 绿色 主体更改。
const theme = getMuiTheme(lightBaseTheme, {
  palette: {
    primary1Color: lightBlue500,
    primary2Color: lightBlue700,
  }
});

export default theme;
