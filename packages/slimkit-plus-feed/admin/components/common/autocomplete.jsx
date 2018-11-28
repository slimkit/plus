import React from 'react';
import PropTypes from 'prop-types';
import AsyncSelect from 'react-select/lib/Async';
import NoSsr from '@material-ui/core/NoSsr';
import TextField from '@material-ui/core/TextField';
import Typography from '@material-ui/core/Typography';
import Paper from '@material-ui/core/Paper';
import Chip from '@material-ui/core/Chip';
import MenuItem from '@material-ui/core/MenuItem';
import CancelIcon from '@material-ui/icons/Cancel';

// styles
import { withStyles } from '@material-ui/core/styles';
import styles from './autocomplete.style';

function NoOptionsMessage(props) {
  return (
    <Typography
      color="textSecondary"
      className={props.selectProps.classes.noOptionsMessage}
      {...props.innerProps}
    >
      {props.children}
    </Typography>
  );
}
  
function inputComponent({ inputRef, ...props }) {
  return <div ref={inputRef} {...props} />;
}
  
function Control(props) {
  return (
    <TextField
      fullWidth={true}
      InputProps={{
        inputComponent,
        inputProps: {
          className: props.selectProps.classes.input,
          inputRef: props.innerRef,
          children: props.children,
          ...props.innerProps,
        },
      }}
      {...props.selectProps.fieldProps}
    />
  );
}
  
function Option(props) {
  return (
    <MenuItem
      buttonRef={props.innerRef}
      selected={props.isFocused}
      component="div"
      style={{
        fontWeight: props.isSelected ? 500 : 400,
      }}
      {...props.innerProps}
    >
      {props.children}
    </MenuItem>
  );
}
  
function Placeholder(props) {
  return (
    <Typography
      color="textSecondary"
      className={props.selectProps.classes.placeholder}
      {...props.innerProps}
    >
      {props.children}
    </Typography>
  );
}
  
function SingleValue(props) {
  return (
    <Typography className={props.selectProps.classes.singleValue} {...props.innerProps}>
      {props.children}
    </Typography>
  );
}
  
function ValueContainer(props) {
  return <div className={props.selectProps.classes.valueContainer}>{props.children}</div>;
}
  
function MultiValue(props) {
  return (
    <Chip
      tabIndex={-1}
      label={props.children}
      className={classNames(props.selectProps.classes.chip, {
        [props.selectProps.classes.chipFocused]: props.isFocused,
      })}
      onDelete={props.removeProps.onClick}
      deleteIcon={<CancelIcon {...props.removeProps} />}
    />
  );
}
  
function Menu(props) {
  return (
    <Paper square className={props.selectProps.classes.paper} {...props.innerProps}>
      {props.children}
    </Paper>
  );
}

const components = {
  Control,
  Menu,
  MultiValue,
  NoOptionsMessage,
  Option,
  Placeholder,
  SingleValue,
  ValueContainer,
};

class Autocomplete extends React.Component
{
    static propTypes = {
      classes: PropTypes.object.isRequired,
      theme: PropTypes.object.isRequired,
      optionsLoader: PropTypes.func.isRequired,
      onChange: PropTypes.func.isRequired,
      value: PropTypes.any,
      fieldProps: PropTypes.object,
      isMulti: PropTypes.bool,
    };

    static defaultProps = {
      fieldProps: {},
      isMulti: false,
    };

    render()
    {
      let { theme, classes, optionsLoader, ...props } = this.props;
      let selectStyles = {
        input: base => ({
          ...base,
          color: theme.palette.text.primary,
          '& input': {
            font: 'inherit',
          },
        }),
      };
      return (
      // <div className={classes.root}>
        <NoSsr>
          <AsyncSelect
            classes={classes}
            styles={selectStyles}
            components={components}
            defaultOptions={false}
            cacheOptions={true}
            loadOptions={optionsLoader}
            {...props}
          />
        </NoSsr>
      // </div>
      );
    }
}

export default withStyles(styles, { withTheme: true })(Autocomplete);
