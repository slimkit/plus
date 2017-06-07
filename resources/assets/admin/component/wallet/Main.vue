<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <!-- 账目报表统计 -->
      <router-link to="/wallet" tag="li" active-class="active" exact>
        <a href="#">统计</a>
      </router-link>

      <!-- 流水 -->
      <router-link to="/wallet/accounts" tag="li" active-class="active">
        <a href="#">流水</a>
      </router-link>

      <!-- 提现 -->
      <router-link to="/wallet/cash" tag="li" active-class="active">
        <a href="#">提现审批</a>
      </router-link>

      <!-- 基础设置相关 -->
      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          基础设置 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <router-link to="/wallet/pay/option" tag="li" active-class="active">
            <a href="#">充值选项</a>
          </router-link>
          <router-link to="/wallet/pay/rule" tag="li" active-class="active">
            <a href="#">规则设置</a>
          </router-link>
          <router-link to="/wallet/pay/ratio" tag="li" active-class="active">
            <a href="#">转换比例</a>
          </router-link>
        </ul>
      </li>
      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          支付设置 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <router-link to="/wallet/pay/type" tag="li" active-class="active">
            <a href="#">支付选项</a>
          </router-link>
          <router-link to="/wallet/pay/pingpp" tag="li" active-class="active">
            <a href="#">Ping++</a>
          </router-link>
        </ul>
      </li>
    </ul>
    <router-view />
  </div>
</template>
