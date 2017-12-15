import { withTheme  } from 'material-ui/styles/';
import { createMuiTheme } from 'material-ui/styles';
import {
  deepPurpleA100, lightBlue700
} from 'material-ui/colors';

// 基于 lightBaseTheme 绿色 主体更改。
const theme = createMuiTheme({
  palette: {
    primary: deepPurpleA100,
    secondary: lightBlue700,
  }
});

export default theme;
