<script setup>
import SideNavLink from "../components/SideNavLink.vue";
import crossSvgIcon from "../assets/icons/cross-svg-icon.vue";
import {useSidebar} from "../stores/sidebar";

const sidebarStore = useSidebar();

const navlinks = [
  {
    label: "dashboard",
    link: "/",
    icon_name: "dashboard-svg-icon",
  },

  {
    label: "deposit",
    link: "/deposits",
    icon_name: "wallet-svg-icon"
  },

  {
    label: "expense",
    link: "/expenses",
    icon_name: "bank-card-svg-icon",
    sub_links: [
      {
        label: "expenses",
        link: "/expenses",
      },
      {
        label: "expense categories",
        link: "/expense-categories",
      },
    ],
  },

  {
    label: "Date wise expenses",
    link: "/expenses-by-date",
    icon_name: "wallet-svg-icon"
  },

  {
    label: "members",
    link: "/members",
    icon_name: "customer-svg-icon",
  },
];
</script>

<template>
  <div id="sidebar" :class="{ active: sidebarStore.open }">
    <div class="sidebar-wrapper active">
      <div class="sidebar-header d-flex">
        <div>
          <img
              src="../assets/img/finnaf-logo.png"
              class="cursor-pointer img-fluid"
              @click="this.$router.push({ name: 'dashboard' })"
              alt="Dashboard"/>
        </div>
        <div class="small-screen-menu-icon ms-3">
          <crossSvgIcon
              width="25px"
              height="25px"
              @click="sidebarStore.toggle()"
          />
        </div>
      </div>
      <div class="sidebar-menu">
        <ul class="menu">
          <SideNavLink
              v-for="link in navlinks"
              :key="link.link"
              :link_details="link"
          />
        </ul>
      </div>
    </div>
  </div>
</template>
