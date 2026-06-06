<template>
  <aside
    :class="[
      'fixed mt-16 flex flex-col lg:mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200',
      {
        'lg:w-[290px]': isExpanded || isMobileOpen || isHovered,
        'lg:w-[90px]': !isExpanded && !isHovered,
        'translate-x-0 w-[290px]': isMobileOpen,
        '-translate-x-full': !isMobileOpen,
        'lg:translate-x-0': true,
      },
    ]"
    @mouseenter="!isExpanded && setIsHovered(true)"
    @mouseleave="setIsHovered(false)"
  >
    <div
      :class="[
        'py-8 flex',
        !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start',
      ]"
    >
      <a href="/">
        <img
          v-if="isExpanded || isHovered || isMobileOpen"
          class="dark:hidden"
          :src="'/images/logo/asb-business-logo.PNG'"
          alt="Logo"
          width="230"
          height="40"
        />
        <img
          v-if="isExpanded || isHovered || isMobileOpen"
          class="hidden dark:block"
          :src="'/images/logo/asb-business-logo-dark.png'"
          alt="Logo"
          width="150"
          height="40"
        />
        <img
          v-else
          :src="'/images/logo/logo-icon.svg'"
          alt="Logo"
          width="32"
          height="32"
        />
      </a>
    </div>
    <div
      class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar"
    >
      <nav class="mb-6">
        <div class="flex flex-col gap-4">
          <div v-for="(menuGroup, groupIndex) in menuGroups" :key="groupIndex">
            <h2
              :class="[
                'mb-4 text-xs uppercase flex leading-[20px] text-gray-400',
                !isExpanded && !isHovered
                  ? 'lg:justify-center'
                  : 'justify-start',
              ]"
            >
              <template v-if="isExpanded || isHovered || isMobileOpen">
                {{ menuGroup.title }}
              </template>
              <HorizontalDots v-else />
            </h2>
            <ul class="flex flex-col gap-4">
              <li v-for="(item, index) in menuGroup.items" :key="item.name">
                <button
                  v-if="item.subItems"
                  @click="toggleSubmenu(groupIndex, index)"
                  :class="[
                    'menu-item group w-full',
                    {
                      'menu-item-active': isSubmenuOpen(groupIndex, index),
                      'menu-item-inactive': !isSubmenuOpen(groupIndex, index),
                    },
                    !isExpanded && !isHovered
                      ? 'lg:justify-center'
                      : 'lg:justify-start',
                  ]"
                >
                  <span
                    :class="[
                      isSubmenuOpen(groupIndex, index)
                        ? 'menu-item-icon-active'
                        : 'menu-item-icon-inactive',
                    ]"
                  >
                    <component :is="item.icon" />
                  </span>
                  <span
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="menu-item-text"
                    >{{ item.name }}</span
                  >
                  <ChevronDownIcon
                    v-if="isExpanded || isHovered || isMobileOpen"
                    :class="[
                      'ml-auto w-5 h-5 transition-transform duration-200',
                      {
                        'rotate-180 text-brand-500': isSubmenuOpen(
                          groupIndex,
                          index
                        ),
                      },
                    ]"
                  />
                </button>
                <a
                  v-else-if="item.path"
                  :href="item.path"
                  :class="[
                    'menu-item group',
                    {
                      'menu-item-active': isActive(item.path),
                      'menu-item-inactive': !isActive(item.path),
                    },
                  ]"
                >
                  <span
                    :class="[
                      isActive(item.path)
                        ? 'menu-item-icon-active'
                        : 'menu-item-icon-inactive',
                    ]"
                  >
                    <component :is="item.icon" />
                  </span>
                  <span
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="menu-item-text"
                    >{{ item.name }}</span
                  >
                </a>
                <transition
                  @enter="startTransition"
                  @after-enter="endTransition"
                  @before-leave="startTransition"
                  @after-leave="endTransition"
                >
                  <div
                    v-show="
                      isSubmenuOpen(groupIndex, index) &&
                      (isExpanded || isHovered || isMobileOpen)
                    "
                  >
                    <ul class="mt-2 space-y-1 ml-9">
                      <li v-for="subItem in item.subItems" :key="subItem.name">
                        <a
                          :href="subItem.path"
                          :class="[
                            'menu-dropdown-item',
                            {
                              'menu-dropdown-item-active': isActive(
                                subItem.path
                              ),
                              'menu-dropdown-item-inactive': !isActive(
                                subItem.path
                              ),
                            },
                          ]"
                        >
                          {{ subItem.name }}
                          <span class="flex items-center gap-1 ml-auto">
                            <span
                              v-if="subItem.new"
                              :class="[
                                'menu-dropdown-badge',
                                {
                                  'menu-dropdown-badge-active': isActive(
                                    subItem.path
                                  ),
                                  'menu-dropdown-badge-inactive': !isActive(
                                    subItem.path
                                  ),
                                },
                              ]"
                            >
                              new
                            </span>
                            <span
                              v-if="subItem.pro"
                              :class="[
                                'menu-dropdown-badge',
                                {
                                  'menu-dropdown-badge-active': isActive(
                                    subItem.path
                                  ),
                                  'menu-dropdown-badge-inactive': !isActive(
                                    subItem.path
                                  ),
                                },
                              ]"
                            >
                              pro
                            </span>
                          </span>
                        </a>
                      </li>
                    </ul>                  </div>
                </transition>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <SidebarWidget v-if="isExpanded || isHovered || isMobileOpen" />
    </div>
  </aside>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

import {
  GridIcon,
  CalenderIcon,
  UserCircleIcon,
  PieChartIcon,
  ChevronDownIcon,
  HorizontalDots,
  PageIcon,
  TableIcon,
  ListIcon,
  PlugInIcon,
} from "../../icons";

import BoxCubeIcon from "@/icons/BoxCubeIcon.vue";
import SidebarWidget from "./SidebarWidget.vue";
import { useSidebar } from "@/composables/useSidebar";

const {
  isExpanded,
  isMobileOpen,
  isHovered,
  setIsHovered,
} = useSidebar();

const currentPath =
  window.location.pathname.replace(/\/$/, "") || "/";
const startTransition = ref();
const endTransition = ref();
const menuGroups = [
    {
        title: "Main",
        items: [
            {
                icon: GridIcon,
                name: "Dashboard",
                path: "/dashboard",
            },
        ],
    },

    {
        title: "Administration",
        items: [
            {
                icon: UserCircleIcon,
                name: "User Management",
                subItems: [
                    {
                        name: "Users",
                        path: "/users",
                    },
                    {
                        name: "Roles",
                        path: "/roles",
                    },
                    {
                        name: "Permissions",
                        path: "/permissions",
                    },
                ],
            },
        ],
    },

    {
        title: "Contacts",
        items: [
            {
                icon: UserCircleIcon,
                name: "Parties",
                subItems: [
                    {
                        name: "Customers",
                        path: "/customers",
                    },
                    {
                        name: "Suppliers",
                        path: "/suppliers",
                    },
                ],
            },
        ],
    },

    {
        title: "Inventory",
        items: [
            {
                icon: BoxCubeIcon,
                name: "Inventory",
                subItems: [
                    {
                        name: "Categories",
                        path: "/categories",
                    },
                    {
                        name: "Products",
                        path: "/products",
                    },
                    {
                        name: "Stock",
                        path: "/stock",
                    },
                ],
            },
        ],
    },

    {
        title: "Transactions",
        items: [
            {
                icon: TableIcon,
                name: "Purchases",
                subItems: [
                    {
                        name: "Purchase Orders",
                        path: "/purchases",
                    },
                    {
                        name: "Purchase Returns",
                        path: "/purchase-returns",
                    },
                ],
            },
            {
                icon: TableIcon,
                name: "Sales",
                subItems: [
                    {
                        name: "Sales",
                        path: "/sales",
                    },
                    {
                        name: "Sale Returns",
                        path: "/sale-returns",
                    },
                ],
            },
        ],
    },

    {
        title: "Petrol Pump",
        items: [
            {
                icon: PlugInIcon,
                name: "Fuel Management",
                subItems: [
                    {
                        name: "Fuel Products",
                        path: "/fuel-products",
                    },
                    {
                        name: "Tanks",
                        path: "/tanks",
                    },
                    {
                        name: "Nozzles",
                        path: "/nozzles",
                    },
                    {
                        name: "Meter Readings",
                        path: "/meter-readings",
                    },
                ],
            },
        ],
    },

    {
        title: "Accounts",
        items: [
            {
                icon: PieChartIcon,
                name: "Accounts",
                subItems: [
                    {
                        name: "Expenses",
                        path: "/expenses",
                    },
                    {
                        name: "Income",
                        path: "/income",
                    },
                    {
                        name: "Cash Book",
                        path: "/cash-book",
                    },
                ],
            },
        ],
    },

    {
        title: "Reports",
        items: [
            {
                icon: PageIcon,
                name: "Reports",
                subItems: [
                    {
                        name: "Sales Report",
                        path: "/reports/sales",
                    },
                    {
                        name: "Purchase Report",
                        path: "/reports/purchases",
                    },
                    {
                        name: "Stock Report",
                        path: "/reports/stock",
                    },
                    {
                        name: "Profit & Loss",
                        path: "/reports/profit-loss",
                    },
                ],
            },
        ],
    },

    {
        title: "Settings",
        items: [
            {
                icon: ListIcon,
                name: "Settings",
                subItems: [
                    {
                        name: "Company",
                        path: "/settings/company",
                    },
                    {
                        name: "System",
                        path: "/settings/system",
                    },
                ],
            },
        ],
    },
];

const openSubmenus = ref({});

const isActive = (path) => {
  return currentPath === path;
};

const toggleSubmenu = (groupIndex, itemIndex) => {
  const key = `${groupIndex}-${itemIndex}`;

  openSubmenus.value[key] = !openSubmenus.value[key];
};

const isSubmenuOpen = (groupIndex, itemIndex) => {
  const key = `${groupIndex}-${itemIndex}`;

  const item = menuGroups[groupIndex].items[itemIndex];

  const hasActiveChild =
    item.subItems?.some((sub) => isActive(sub.path));

  return openSubmenus.value[key] || hasActiveChild;
};
</script>