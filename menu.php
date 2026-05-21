<?php

function renderMenu($activePage, $activeSort): string
{
    $items = [
        'view' => 'Просмотр',
        'add' => 'Добавление записи',
        'edit' => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];

    $html = '<div class="menu">';

    foreach ($items as $key => $value) {

        $class = ($activePage === $key)
            ? 'menu-link active'
            : 'menu-link';

        $html .= "
            <a class='$class' href='index.php?page=$key'>
                $value
            </a>
        ";
    }

    $html .= '</div>';

    if ($activePage === 'view') {

        $sorts = [
            'created' => 'По добавлению',
            'surname' => 'По фамилии',
            'birthdate' => 'По дате рождения'
        ];

        $html .= '<div class="submenu">';

        foreach ($sorts as $key => $value) {

            $class = ($activeSort === $key)
                ? 'menu-link small active'
                : 'menu-link small';

            $html .= "
                <a class='$class'
                   href='index.php?page=view&sort=$key'>
                    $value
                </a>
            ";
        }

        $html .= '</div>';
    }

    return $html;
}