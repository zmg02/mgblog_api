<?php

return [
    'admin' => [
        [
            "title" => "成员管理",
            "uri" => "/member",
            "children" => [
                [
                    "title" => "用户",
                    "uri" => "/member/user"
                ],
                [
                    "title" => "作者",
                    "uri" => "/member/author"
                ],
                [
                    "title" => "管理员",
                    "uri" => "/member/admin"
                ]
            ]
        ],
        [
            "title" => "文章管理",
            "uri" => "/article",
            "children" => [
                [
                    "title" => "文章列表",
                    "uri" => "/article/list"
                ],
                [
                    "title" => "文章分类",
                    "uri" => "/article/category"
                ]
            ]
        ],
        [
            "title" => "测试",
            "uri" => "/test",
            "children" => [
                [
                    "title" => "测试1",
                    "uri" => "/test/test1",
                    "children" => [
                        [
                            "title" => "测试1-1",
                            "uri" => "/test/test1/test1-1",
                            "children" => [
                                [
                                    "title" => "测试1-1-1",
                                    "uri" => "/test/test1/test1-1/test1-1-1"
                                ],
                                [
                                    "title" => "测试1-1-2",
                                    "uri" => "/test/test1/test1-1/test1-1-2"
                                ],
                                [
                                    "title" => "测试1-1-3",
                                    "uri" => "/test/test1/test1-1/test1-1-3"
                                ],
                            ]
                        ],
                        [
                            "title" => "测试1-2",
                            "uri" => "/test/test1/test1-2",
                        ]
                    ]
                ],
                [
                    "title" => "测试2",
                    "uri" => "/test/test2",
                    "children" => [
                        [
                            "title" => "测试2-1",
                            "uri" => "/test/test2/test2-1",
                        ],
                        [
                            "title" => "测试2-2",
                            "uri" => "/test/test2/test2-2",
                        ]
                    ]
                ]
            ]
        ],
        [
            "title" => "默认路由",
            "uri" => "/default",
            "children" => [
                [
                    "title" => "默认路由",
                    "uri" => "/default/index"
                ]
            ]
        ]
    ],
    'vip' => [
        [
            "title" => "成员管理",
            "uri" => "/member",
            "children" => [
                [
                    "title" => "用户",
                    "uri" => "/member/user"
                ],
                [
                    "title" => "作者",
                    "uri" => "/member/author"
                ]
            ]
        ],
        [
            "title" => "文章管理",
            "uri" => "/article",
            "children" => [
                [
                    "title" => "文章列表",
                    "uri" => "/article/list"
                ],
                [
                    "title" => "文章分类",
                    "uri" => "/article/category"
                ]
            ]
        ],
        [
            "title" => "测试",
            "uri" => "/test",
            "children" => [
                [
                    "title" => "测试1",
                    "uri" => "/test/test1",
                    "children" => [
                        [
                            "title" => "测试1-1",
                            "uri" => "/test/test1/test1-1",
                            "children" => [
                                [
                                    "title" => "测试1-1-3",
                                    "uri" => "/test/test1/test1-1/test1-1-3"
                                ],
                            ]
                        ],
                        [
                            "title" => "测试1-2",
                            "uri" => "/test/test1/test1-2",
                        ]
                    ]
                ],
                [
                    "title" => "测试2",
                    "uri" => "/test/test2",
                    "children" => [
                        [
                            "title" => "测试2-1",
                            "uri" => "/test/test2/test2-1",
                        ],
                        [
                            "title" => "测试2-2",
                            "uri" => "/test/test2/test2-2",
                        ]
                    ]
                ]
            ]
        ]
    ]
];
