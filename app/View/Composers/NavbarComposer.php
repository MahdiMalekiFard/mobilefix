<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\Address;
use App\Models\Blog;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Opinion;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Problem;
use App\Models\Service;
use App\Models\User;
use App\Services\Permissions\PermissionsService;
use Illuminate\View\View;

class NavbarComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();
        $view->with('navbarMenu', [
            [
                'icon'       => 's-home',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.dashboard'),
                'route_name' => 'admin.dashboard',
            ],
            // [
            //     'icon'       => 's-ticket',
            //     'params'     => [],
            //     'exact'      => true,
            //     'title'      => trans('_menu.ticket_management'),
            //     'route_name' => 'admin.ticket.index',
            //     'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Index')),
            // ],
            // [
            //     'icon'       => 'lucide.key-round',
            //     'params'     => [],
            //     'exact'      => true,
            //     'title'      => trans('_menu.role_management'),
            //     'route_name' => 'admin.role.index',
            //     'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Index')),
            // ],
            [
                'icon'     => 's-users',
                'title'    => trans('_menu.user_management'),
                'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Store', 'Index')),
                'sub_menu' => [
                    [
                        'icon'       => 's-list-bullet',
                        'route_name' => 'admin.user.index',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.user.all'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-user-plus',
                        'route_name' => 'admin.user.create',
                        'params'     => [],
                        'title'      => trans('_menu.user.create'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Store')),
                    ],
                ],
            ],
            [
                'icon'     => 's-squares-2x2',
                'title'    => trans('_menu.category_management'),
                'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Store', 'Index')),
                'sub_menu' => [
                    [
                        'icon'       => 's-list-bullet',
                        'route_name' => 'admin.category.index',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.category.all'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-plus-circle',
                        'route_name' => 'admin.category.create',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.category.create'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Store')),
                    ],
                ],
            ],
            [
                'icon'     => 's-book-open',
                'title'    => trans('_menu.blog_management'),
                'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Store', 'Index')),
                'sub_menu' => [
                    [
                        'icon'       => 's-list-bullet',
                        'route_name' => 'admin.blog.index',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.blog.all'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-plus-circle',
                        'route_name' => 'admin.blog.create',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.blog.create'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Store')),
                    ],
                ],
            ],
            [
                'icon'     => 's-star',
                'title'    => trans('_menu.service_management'),
                'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Store', 'Index')),
                'sub_menu' => [
                    [
                        'icon'       => 's-list-bullet',
                        'route_name' => 'admin.service.index',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.service.all'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-plus-circle',
                        'route_name' => 'admin.service.create',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.service.create'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Store')),
                    ],
                ],
            ],
            [
                'icon'     => 's-sparkles',
                'title'    => trans('_menu.opinion_management'),
                'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Store', 'Index')),
                'sub_menu' => [
                    [
                        'icon'       => 's-list-bullet',
                        'route_name' => 'admin.opinion.index',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.opinion.all'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-plus-circle',
                        'route_name' => 'admin.opinion.create',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.opinion.create'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Store')),
                    ],
                ],
            ],
            [
                'icon'     => 's-shopping-cart',
                'title'    => trans('_menu.order_management'),
                'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Store', 'Index')),
                'sub_menu' => [
                    [
                        'icon'       => 's-list-bullet',
                        'route_name' => 'admin.order.index',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.order.all'),
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Index')),
                    ],
                ],
            ],
            [
                'icon'     => 's-chat-bubble-left-right',
                'title'    => trans('_menu.contact_us_management'),
                'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Index')),
                'sub_menu' => [
                    [
                        'icon'       => 's-list-bullet',
                        'route_name' => 'admin.contact-us.index',
                        'exact'      => true,
                        'params'     => [],
                        'title'      => trans('_menu.contact_us.all'),
                    ],
                    [
                        'icon'          => 's-eye-slash',
                        'route_name'    => 'admin.contact-us.index',
                        'exact'         => false,
                        'params'        => ['filters' => ['is_read' => 0]],
                        'title'         => trans('_menu.contact_us.unread'),
                        'badge'         => ContactUs::unread()->count() > 0 ? ContactUs::unread()->count() : null,
                        'badge_classes' => 'bg-red-500 text-white text-xs px-2 py-1 rounded-full',
                    ],
                ],
            ],
            [
                'icon'       => 's-credit-card',
                'title'      => trans('_menu.payment_method_management'),
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class, 'Store', 'Index')),
                'route_name' => 'admin.payment-method.index',
            ],
            [
                'icon'       => 's-question-mark-circle',
                'title'      => trans('_menu.problems'),
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class, 'Store', 'Index')),
                'route_name' => 'admin.problem.index',
            ],
            [
                'icon'       => 's-map-pin',
                'title'      => trans('_menu.addresses'),
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class, 'Store', 'Index')),
                'route_name' => 'admin.address.index',
            ],
            // [
            //     'icon'     => 's-tag',
            //     'title'    => 'مدیریت تگ ها',
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-list-bullet',
            //             'route_name' => 'admin.tag.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => 'همه',
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-plus-circle',
            //             'route_name' => 'admin.tag.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => 'ایجاد',
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 's-sparkles',
            //     'title'    => trans('_menu.opinion_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-list-bullet',
            //             'route_name' => 'admin.opinion.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.opinion.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Index')),

            //         ],
            //         [
            //             'icon'       => 's-plus-circle',
            //             'route_name' => 'admin.opinion.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.opinion.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Store')),

            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 's-arrow-left-start-on-rectangle',
            //     'title'    => trans('_menu.slider_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-list-bullet',
            //             'route_name' => 'admin.slider.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.slider.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-plus-circle',
            //             'route_name' => 'admin.slider.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.slider.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 's-question-mark-circle',
            //     'title'    => trans('_menu.faq_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-list-bullet',
            //             'route_name' => 'admin.faq.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.faq.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-plus-circle',
            //             'route_name' => 'admin.faq.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.faq.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 's-chat-bubble-left-right',
            //     'title'    => trans('_menu.comment_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-list-bullet',
            //             'route_name' => 'admin.comment.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.comment.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-plus-circle',
            //             'route_name' => 'admin.comment.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.comment.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 's-photo',
            //     'title'    => trans('_menu.banner_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-home',
            //             'route_name' => 'admin.banner.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.banner.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-home',
            //             'route_name' => 'admin.banner.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.banner.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 'lucide.square-user-round',
            //     'title'    => trans('_menu.client_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-home',
            //             'route_name' => 'admin.client.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.client.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-home',
            //             'route_name' => 'admin.client.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.client.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 'lucide.users',
            //     'title'    => trans('_menu.teammate_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-home',
            //             'route_name' => 'admin.teammate.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.teammate.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-home',
            //             'route_name' => 'admin.teammate.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.teammate.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 'lucide.gallery-horizontal-end',
            //     'title'    => trans('_menu.portfolio_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-list-bullet',
            //             'route_name' => 'admin.portFolio.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.portfolio.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-plus-circle',
            //             'route_name' => 'admin.portFolio.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.portfolio.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Store')),
            //         ]
            //     ],
            // ],
            // [
            //     'icon'     => 'lucide.layers',
            //     'title'    => trans('_menu.page_management'),
            //     'access'   => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Store', 'Index')),
            //     'sub_menu' => [
            //         [
            //             'icon'       => 's-list-bullet',
            //             'route_name' => 'admin.page.index',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.page.all'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Index')),
            //         ],
            //         [
            //             'icon'       => 's-plus-circle',
            //             'route_name' => 'admin.page.create',
            //             'exact'      => true,
            //             'params'     => [],
            //             'title'      => trans('_menu.page.create'),
            //             'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Store')),
            //         ]
            //     ],
            // ],
        ]);
    }

    private function hasAccessGroup(User $user, array $array): bool
    {
        $result = [];
        foreach ($array as $model => $permissions) {
            $result[] = $user->hasAnyPermission(PermissionsService::generatePermissionsByModel($model, $permissions));
        }

        return in_array(true, $result, true);
    }
}
