import React from 'react'
import { Link } from 'react-router-dom'

import * as core from '@material-ui/core'
import * as style from '@material-ui/core/styles'
import * as icon from '@material-ui/icons'

import ListOperators from './ListOperatorsComponent'
import SetUploadFile from './UploadFileComponent'
import SpinnerComponent from './SpinnerComponent'
import NoticeModal from './NoticeComponent'

import { ajaxAction } from '../services'

const drawerWidth = 240

const useStyles = style.makeStyles(( theme: style.Theme ) =>
  style.createStyles({
    root: {
      display: 'flex',
    },
    drawer: {
      [theme.breakpoints.up( 'sm' )]: {
        width: drawerWidth,
        flexShrink: 0,
      },
    },
    appBar: {
      [theme.breakpoints.up( 'sm' )]: {
        width: `calc(100% - ${drawerWidth}px)`,
        marginLeft: drawerWidth,
      },
    },
    menuButton: {
      marginRight: theme.spacing( 2 ),
      [theme.breakpoints.up('sm')]: {
        display: 'none',
      },
    },
    toolbar: theme.mixins.toolbar,
    drawerPaper: {
      width: drawerWidth,
    },
    content: {
      flexGrow: 1,
      padding: theme.spacing( 3 ),
    },
  })
)

interface DashBoardComponentProps {
  container?: Element
}

export function DashBoardComponent( props: DashBoardComponentProps ) {
  const { container } = props
  const classes = useStyles()
  const theme = style.useTheme()
  const [mobileOpen, setMobileOpen] = React.useState( false )
  const handleDrawerToggle = () => {
    setMobileOpen( !mobileOpen )
  }
  
  const [titleText, setTitleText] = React.useState( '' )
  const [users, setUsers] = React.useState([])
  
  const [operatorDiv, setVisibleOperatorDiv] = React.useState( false )
  const [spinnerDiv, setVisibleSpinnerDiv] = React.useState( false )
  const [reportDiv, setVisibleReportDiv] = React.useState( false )
  /*  
      !  uploadDiv используется два раза: 
      1. для загрузки пользователей из файла
      2. для загрузки базы контактов из файла
  */
  const [uploadDiv, setVisibleUploadDiv] = React.useState( '' )
  const [noticeModal, setVisibleNoticeModal] = React.useState( false )
  
  const setUploadBase = () => {
    setTitleText( 'Загрузить базу' )
    setVisibleUploadDiv( 'base' )
    setVisibleOperatorDiv( false )
    setVisibleSpinnerDiv( false )
    setVisibleReportDiv( false )
    setVisibleNoticeModal( false )
  }

  const setUploadUser = () => {
    setTitleText( 'Загрузить пользователей' )
    setVisibleReportDiv( false )
    setVisibleOperatorDiv( false )
    setVisibleSpinnerDiv( false )
    setVisibleNoticeModal( false )
    setVisibleUploadDiv( 'user' )
    
  }

  const getReport = async () => {
    setTitleText( 'Выгрузка отчета' )
    setVisibleReportDiv( false )
    setVisibleOperatorDiv( false )
    setVisibleUploadDiv( '' )
    const url : string = 'report'
    const method : string = 'GET'
    setVisibleSpinnerDiv( true )
    const ret : any = await ajaxAction( url, method )
    if ( ret  ) {
      setVisibleSpinnerDiv( false )
      setVisibleNoticeModal( true )
      setVisibleReportDiv( true )
    }
  }
  const getUsers = async () => {
    setTitleText( 'Назначить старшего оператора' )
    setVisibleSpinnerDiv( false )
    setVisibleReportDiv( false )
    setVisibleUploadDiv( '' )
    setVisibleNoticeModal( false )
    const url : string = 'user'
    const method : string = 'GET'
    const operators: any = await ajaxAction( url, method )
    const { data } = operators
    setUsers( data )
    setVisibleOperatorDiv( true )
  }
  const drawer = (
    <div>
      <Link
        to = '/main'
        style = {{
          fontSize: 18,
          textAlign: 'center',
          display: 'block',
          marginTop: 20,
        }}
      >
        На главную
      </Link>
      <core.Divider style = {{ marginTop: 20 }} />
      <core.List>
        <core.ListItem button key = { 'Загрузить базу' } onClick = { setUploadBase }>
          <core.ListItemIcon>
            <core.IconButton
              color = 'primary'
              aria-label = 'upload picture'
              component = 'span'
            >
              <icon.LocalAirportRounded />
            </core.IconButton>
          </core.ListItemIcon>
          <core.ListItemText primary = 'Загрузить базу' />
        </core.ListItem>

        <core.ListItem button key = { 'Загрузить пользователей' } onClick = { setUploadUser }>
          <core.ListItemIcon>
            <core.IconButton
              color = 'primary'
              aria-label = 'upload picture'
              component = 'span'
            >
              <icon.PersonAdd/>
            </core.IconButton>
          </core.ListItemIcon>
          <core.ListItemText primary = { 'Загрузить пользователей' } />
        </core.ListItem>
        <core.ListItem
          button
          key = { 'Назначить старших операторов' }
          onClick = { getUsers }
        >
          <core.ListItemIcon>
            <core.IconButton
              color = 'primary'
              aria-label = 'upload picture'
              component = 'span'
            >
              <icon.PeopleAlt />
            </core.IconButton>
          </core.ListItemIcon>
          <core.ListItemText primary = { 'Назначить старших операторов' } />
        </core.ListItem>
      </core.List>
      <core.Divider />
      <core.List>
        <core.ListItem button key = { 'Выгрузить отчет' } onClick = { getReport }>
          <core.ListItemIcon>
            <core.IconButton
              color = 'primary'
              aria-label = 'upload picture'
              component = 'span'
            >
              <icon.WorkOutline />
            </core.IconButton>
          </core.ListItemIcon>
          <core.ListItemText primary = { 'Выгрузить отчет' } />
        </core.ListItem>
        <core.ListItem button key = { 'Графики звонков' }>
          <core.ListItemIcon>
            <core.IconButton
              color = 'primary'
              aria-label = 'upload picture'
              component = 'span'
            >
              <icon.ShowChart />
            </core.IconButton>
          </core.ListItemIcon>
          <core.ListItemText primary = { 'Графики звонков' } />
        </core.ListItem>
      </core.List>
    </div>
  )

  return (
    <div className = { classes.root }>
      <core.CssBaseline />
      <core.AppBar position = 'fixed' className = { classes.appBar }>
        <core.Toolbar>
          <core.IconButton
            color = 'inherit'
            aria-label = 'open drawer'
            edge = 'start'
            onClick = { handleDrawerToggle }
            className = { classes.menuButton }
          >
            <icon.Menu />
          </core.IconButton>
          <core.Typography
            variant = 'h6'
            noWrap
            style = {{ paddingLeft: -30, margin: 'auto', paddingRight: 44 }}
          >
            { titleText ? `${titleText}` : 'Панель управления' }
          </core.Typography>
        </core.Toolbar>
      </core.AppBar>
      <nav className = { classes.drawer } aria-label = 'mailbox folders'>
        <core.Hidden smUp implementation='css'>
          <core.Drawer
            container = { container }
            variant = 'temporary'
            anchor = { theme.direction === 'rtl' ? 'right' : 'left' }
            open = { mobileOpen }
            onClose = { handleDrawerToggle }
            classes = {{ paper: classes.drawerPaper }}
            ModalProps = {{ keepMounted: true }}
          >
            { drawer }
          </core.Drawer>
        </core.Hidden>
        <core.Hidden xsDown implementation='css'>
          <core.Drawer
            classes = {{ paper: classes.drawerPaper }}
            variant = 'permanent'
            open
          >
            { drawer }
          </core.Drawer>
        </core.Hidden>
      </nav>
      <main className = { classes.content }>
        <div className = { classes.toolbar } />
        { uploadDiv ? (
          <core.Grid item xs = { 12 } lg = { 3 } sm = { 4 } md = { 4 } style = {{ marginBottom: 20 }}>
            <SetUploadFile url = { uploadDiv } />
          </core.Grid>
        ) : null }
        { operatorDiv ? <ListOperators users= { users }/> : null }
        { spinnerDiv ? (
          <core.Grid item xs = { 12 } lg = { 2 } sm = { 4 } md = { 4 } style = {{ marginBottom: 20 }}>
            <div style = {{ marginLeft: '130px' }}>
              <SpinnerComponent />
            </div>
          </core.Grid>
        ) : null }
        { reportDiv ? (
          <core.Grid item xs = { 12 } lg = { 3 } sm = { 4 } md = { 4 } style = {{ marginBottom: 20 }}>
            <core.Button
              href = 'http://localhost/komus_new/report.xlsx'
              variant = 'outlined'
              color = 'primary'
              style = {{
                width: '100%',
                margin: 'auto',
                height: 55,
                marginTop: 5,
              }}
            >
              Скачать отчет
            </core.Button>
          </core.Grid>
        ) : null }
        { noticeModal ? <NoticeModal /> : null }
      </main>
    </div>
  )
}
