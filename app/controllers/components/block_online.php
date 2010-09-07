<?php
App::import('component', 'BlocksParent');

class BlockOnlineComponent extends BlocksParentComponent
{

    /**
     * Получаем список онлайн юзеров.
     * в данный момент не используется
     *
     * @param unknown_type $args
     * @return unknown
     */
    function onlineBlock($args)
    {
        if ($stats = Cache::read('Block.onlineUsers', 'default'))
            return $stats;

        $time = 900;
        if (isset($args['time']))
            $time = $args['time'];

        $cache = '+1 minute';
        if (isset($args['cache']))
            $cache = $args['cache'];


        $sql = 'SELECT user.username, (user.options & 512) AS invisible, user.usergroupid,
            session.userid, session.inforum, session.lastactivity, groups.opentag, groups.closetag,
            IF(displaygroupid=0, user.usergroupid, displaygroupid) AS displaygroupid
        FROM session AS session
        LEFT JOIN user AS user ON (user.userid = session.userid)
        LEFT JOIN usergroup AS groups ON (groups.usergroupid = user.usergroupid)
        WHERE session.lastactivity > ' . $time .' GROUP BY username
        ORDER BY username ASC';

        $users = $this->controller->User->query($sql);
        //pr($users);
        $active = array('guests' => 0, 'anon' => 0, 'members' => 0, 'names' => array());

        foreach ($users as $user)
        {
            if ($user['session']['userid'] == 0)
            {
                $active['guests']++;
                continue;
            }

            if ($user[0]['invisible'] == 512)
            {
                    $active['anon']++;
            }
            else
            {
                $active['members']++;
                $active['names'][] = array('id' => $user['session']['userid'],
                                           'username' => $user['groups']['opentag'] . $user['user']['username'] . $user['groups']['closetag']);
            }
        }

        $stats = array('members' => $active['members'], 'anon' => $active['anon'],
                     'guests' => $active['guests'], 'names' => $active['names'],
                     'total' => $active['members'] + $active['guests'] + $active['anon']);
        Cache::config('default');
        Cache::write('Block.onlineUsers', $stats, $cache);
        return $stats;
    }


}
?>