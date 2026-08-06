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
        <!-- Logo -->

        <div
            :class="[
                'py-8 flex',
                !isExpanded && !isHovered
                    ? 'lg:justify-center'
                    : 'justify-start',
            ]"
        >
            <a href="/">
                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="dark:hidden"
                    :src='"/images/logo/narrow-logo.jpg"'
                    width="230"
                />

                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="hidden dark:block"
                    :src='"/images/logo/narrow-logo.jpg"'
                    width="230"
                />

                <img
                    v-else
                    :src='"/images/logo/logo-icon.svg"'
                    width="34"
                />
            </a>
        </div>

        <!-- Navigation -->

        <div
            class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar"
        >
            <nav class="mb-6">
                <div class="flex flex-col gap-4">
                    <div
                        v-for="(menuGroup, groupIndex) in menuGroups"
                        :key="groupIndex"
                    >
                        <!-- Group Heading -->

                        <h2
                            :class="[
                                'mb-4 text-xs uppercase flex leading-[20px] text-gray-400',
                                !isExpanded && !isHovered
                                    ? 'lg:justify-center'
                                    : 'justify-start',
                            ]"
                        >
                            <template
                                v-if="
                                    isExpanded ||
                                    isHovered ||
                                    isMobileOpen
                                "
                            >
                                {{ menuGroup.title }}
                            </template>

                            <HorizontalDots v-else />
                        </h2>

                        <!-- Group Items -->

                        <ul class="flex flex-col gap-4">
                            <li
                                v-for="(item, itemIndex) in menuGroup.items"
                                :key="item.name"
                            >
                                <!-- Parent -->

                                <button
                                    v-if="item.subItems"
                                    @click="
                                        toggleSubmenu(
                                            groupIndex,
                                            itemIndex
                                        )
                                    "
                                    :class="[
                                        'menu-item group w-full',
                                        {
                                            'menu-item-active':
                                                isSubmenuOpen(
                                                    groupIndex,
                                                    itemIndex
                                                ),
                                            'menu-item-inactive':
                                                !isSubmenuOpen(
                                                    groupIndex,
                                                    itemIndex
                                                ),
                                        },
                                        !isExpanded && !isHovered
                                            ? 'lg:justify-center'
                                            : 'lg:justify-start',
                                    ]"
                                >
                                    <span
                                        :class="[
                                            isSubmenuOpen(
                                                groupIndex,
                                                itemIndex
                                            )
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive',
                                        ]"
                                    >
                                        <component :is="item.icon" />
                                    </span>

                                    <span
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        class="menu-item-text"
                                    >
                                        {{ item.name }}
                                    </span>

                                    <ChevronDownIcon
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        :class="[
                                            'ml-auto h-5 w-5 transition-transform duration-200',
                                            {
                                                'rotate-180 text-brand-500':
                                                    isSubmenuOpen(
                                                        groupIndex,
                                                        itemIndex
                                                    ),
                                            },
                                        ]"
                                    />
                                </button>

                                <!-- Single Link -->

                                <a
                                    v-else-if="item.path"
                                    :href="item.path"
                                    :class="[
                                        'menu-item group',
                                        {
                                            'menu-item-active':
                                                isActive(item.path),
                                            'menu-item-inactive':
                                                !isActive(item.path),
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
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        class="menu-item-text"
                                    >
                                        {{ item.name }}
                                    </span>
                                </a>

                                <!-- Children -->

                                <transition
                                    @enter="startTransition"
                                    @after-enter="endTransition"
                                    @before-leave="startTransition"
                                    @after-leave="endTransition"
                                >
                                    <div
                                        v-show="
                                            isSubmenuOpen(
                                                groupIndex,
                                                itemIndex
                                            ) &&
                                            (isExpanded ||
                                                isHovered ||
                                                isMobileOpen)
                                        "
                                    >
                                        <ul
                                            class="ml-9 mt-2 space-y-1"
                                        >
                                            <li
                                                v-for="subItem in item.subItems"
                                                :key="subItem.name"
                                            >
                                                <a
                                                    :href="subItem.path"
                                                    :class="[
                                                        'menu-dropdown-item',
                                                        {
                                                            'menu-dropdown-item-active':
                                                                isActive(
                                                                    subItem.path
                                                                ),
                                                            'menu-dropdown-item-inactive':
                                                                !isActive(
                                                                    subItem.path
                                                                ),
                                                        },
                                                    ]"
                                                >
                                                    {{ subItem.name }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </transition>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <SidebarWidget
                v-if="
                    isExpanded ||
                    isHovered ||
                    isMobileOpen
                "
            />
        </div>
    </aside>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

import {
    GridIcon,
    UserCircleIcon,
    PieChartIcon,
    ChevronDownIcon,
    HorizontalDots,
    PageIcon,
    ListIcon,
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

/*
|--------------------------------------------------------------------------
| Sidebar Menu
|--------------------------------------------------------------------------
*/

const menuGroups = [
    /*
    |--------------------------------------------------------------------------
    | Main
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Administration
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Academic Management
    |--------------------------------------------------------------------------
    */
    {
        title: "Academic",
        items: [
            {
                icon: BoxCubeIcon,
                name: "Academic Management",
                subItems: [
                    {
                        name: "Faculties",
                        path: "/academic/faculties",
                    },
                    {
                        name: "Disciplines",
                        path: "/academic/disciplines",
                    },
                    {
                        name: "Academic Sessions",
                        path: "/academic/sessions",
                    },
                    {
                        name: "Classes",
                        path: "/academic/classes",
                    },
                    {
                        name: "Students",
                        path: "/academic/students",
                    },

                    /*
                    |--------------------------------------------------------------------------
                    | Upcoming Modules
                    |--------------------------------------------------------------------------
                    */

                    {
                        name: "Student Promotions",
                        path: "/academic/promotions",
                    },
                    {
                        name: "Student Transfers",
                        path: "/academic/transfers",
                    },
                    {
                        name: "Student Strike Off",
                        path: "/academic/strike-off",
                    },
                    {
                        name: "Alumni",
                        path: "/academic/alumni",
                    },
                ],
            },
        ],
    },

    /*
    |--------------------------------------------------------------------------
    | Teaching
    |--------------------------------------------------------------------------
    */

    {
        title: "Teaching",
        items: [
            {
                icon: UserCircleIcon,
                name: "Teaching",
                subItems: [
                    {
                        name: "Teachers",
                        path: "/teachers",
                    },
                    {
                        name: "Subjects",
                        path: "/subjects",
                    },
                    {
                        name: "Subject Groups",
                        path: "/subject-groups",
                    },
                    {
                        name: "Class Allocation",
                        path: "/class-allocation",
                    },
                    {
                        name: "Timetable",
                        path: "/timetable",
                    },
                    {
                        name: "Attendance",
                        path: "/attendance",
                    },
                ],
            },
        ],
    },
        /*
    |--------------------------------------------------------------------------
    | Examination Management
    |--------------------------------------------------------------------------
    */

    {
        title: "Examinations",
        items: [
            {
                icon: PageIcon,
                name: "Examinations",
                subItems: [
                    {
                        name: "Exams",
                        path: "/examinations",
                    },
                    {
                        name: "Marks Entry",
                        path: "/examinations/marks",
                    },
                    {
                        name: "Results",
                        path: "/results",
                    },
                    {
                        name: "Report Cards",
                        path: "/report-cards",
                    },
                    {
                        name: "Certificates",
                        path: "/certificates",
                    },
                ],
            },
        ],
    },


    /*
    |--------------------------------------------------------------------------
    | Finance Management
    |--------------------------------------------------------------------------
    */

    {
        title: "Finance",
        items: [
            {
                icon: PieChartIcon,
                name: "Accounts",
                subItems: [
                    {
                        name: "Fee Structures",
                        path: "/finance/fee-structures",
                    },
                    {
                        name: "Student Fees",
                        path: "/finance/student-fees",
                    },
                    {
                        name: "Fee Collection",
                        path: "/finance/collections",
                    },
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


    /*
    |--------------------------------------------------------------------------
    | Inventory Management
    |--------------------------------------------------------------------------
    */

    {
        title: "Inventory",
        items: [
            {
                icon: BoxCubeIcon,
                name: "Inventory",
                subItems: [
                    {
                        name: "Items",
                        path: "/inventory/items",
                    },
                    {
                        name: "Categories",
                        path: "/inventory/categories",
                    },
                    {
                        name: "Suppliers",
                        path: "/inventory/suppliers",
                    },
                    {
                        name: "Stock Management",
                        path: "/inventory/stock",
                    },
                    {
                        name: "Assets",
                        path: "/inventory/assets",
                    },
                ],
            },
        ],
    },


    /*
    |--------------------------------------------------------------------------
    | Library Management
    |--------------------------------------------------------------------------
    */

    {
        title: "Library",
        items: [
            {
                icon: ListIcon,
                name: "Library",
                subItems: [
                    {
                        name: "Books",
                        path: "/library/books",
                    },
                    {
                        name: "Categories",
                        path: "/library/categories",
                    },
                    {
                        name: "Issue Books",
                        path: "/library/issues",
                    },
                    {
                        name: "Return Books",
                        path: "/library/returns",
                    },
                ],
            },
        ],
    },


    /*
    |--------------------------------------------------------------------------
    | Hostel Management
    |--------------------------------------------------------------------------
    */

    {
        title: "Hostel",
        items: [
            {
                icon: UserCircleIcon,
                name: "Hostel",
                subItems: [
                    {
                        name: "Rooms",
                        path: "/hostel/rooms",
                    },
                    {
                        name: "Hostel Allocation",
                        path: "/hostel/allocation",
                    },
                    {
                        name: "Hostel Students",
                        path: "/hostel/students",
                    },
                ],
            },
        ],
    },


    /*
    |--------------------------------------------------------------------------
    | Transport Management
    |--------------------------------------------------------------------------
    */

    {
        title: "Transport",
        items: [
            {
                icon: BoxCubeIcon,
                name: "Transport",
                subItems: [
                    {
                        name: "Vehicles",
                        path: "/transport/vehicles",
                    },
                    {
                        name: "Routes",
                        path: "/transport/routes",
                    },
                    {
                        name: "Students",
                        path: "/transport/students",
                    },
                ],
            },
        ],
    },
        /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    {
        title: "Reports",
        items: [
            {
                icon: PageIcon,
                name: "Reports",
                subItems: [
                    {
                        name: "Student Reports",
                        path: "/reports/students",
                    },
                    {
                        name: "Admission Reports",
                        path: "/reports/admissions",
                    },
                    {
                        name: "Attendance Reports",
                        path: "/reports/attendance",
                    },
                    {
                        name: "Examination Reports",
                        path: "/reports/examinations",
                    },
                    {
                        name: "Fee Reports",
                        path: "/reports/fees",
                    },
                    {
                        name: "Inventory Reports",
                        path: "/reports/inventory",
                    },
                ],
            },
        ],
    },


    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    {
        title: "Settings",
        items: [
            {
                icon: ListIcon,
                name: "Settings",
                subItems: [
                    {
                        name: "Company Settings",
                        path: "/settings/company",
                    },
                    {
                        name: "Academic Settings",
                        path: "/settings/academic",
                    },
                    {
                        name: "System Settings",
                        path: "/settings/system",
                    },
                    {
                        name: "Backup",
                        path: "/settings/backup",
                    },
                ],
            },
        ],
    },
];


/*
|--------------------------------------------------------------------------
| Submenu State
|--------------------------------------------------------------------------
*/

const openSubmenus = ref({});


/*
|--------------------------------------------------------------------------
| Active Route Check
|--------------------------------------------------------------------------
*/

const isActive = (path) => {
    return currentPath === path;
};


/*
|--------------------------------------------------------------------------
| Toggle Submenu
|--------------------------------------------------------------------------
*/

const toggleSubmenu = (groupIndex, itemIndex) => {

    const key = `${groupIndex}-${itemIndex}`;

    openSubmenus.value[key] =
        !openSubmenus.value[key];

};


/*
|--------------------------------------------------------------------------
| Check Submenu Open
|--------------------------------------------------------------------------
*/

const isSubmenuOpen = (groupIndex, itemIndex) => {

    const key = `${groupIndex}-${itemIndex}`;

    const item =
        menuGroups[groupIndex]
            .items[itemIndex];


    const hasActiveChild =
        item.subItems?.some(
            (sub) =>
                isActive(sub.path)
        );


    return (
        openSubmenus.value[key] ||
        hasActiveChild
    );

};

</script>