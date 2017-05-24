<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <router-link to="/wallet" tag="li" active-class="active" exact>
        <a href="#">统计</a>
      </router-link>
      <router-link to="/wallet/accounts" tag="li" active-class="active" exact>
        <a href="#">流水</a>
      </router-link>
      <router-link to="/wallet/cash" tag="li" active-class="active" exact>
        <a href="#">提现审批</a>
      </router-link>
      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          基础设置 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a href="#">充值选项</a>
          </li>
          <li>
            <a href="#">规则设置</a>
          </li>
        </ul>
      </li>
      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          支付设置 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a href="#">Apple Pay</a>
          </li>
          <li>
            <a href="#">支付宝</a>
          </li>
          <li>
            <a href="#">微信支付</a>
          </li>
        </ul>
      </li>
    </ul>
    <router-view />
  </div>
</template>
