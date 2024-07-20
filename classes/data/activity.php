<?php
// Standard GPL and phpdocs

namespace local_odysseus\data;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

class activity implements renderable, templatable {
    /** @var string $sometext Some text to show how to pass data to a template. */
    private $params = null;

    public function __construct($params) {
        $this->params = $params;
    }

    /**
     * Load records from DB filtered by $params.
     *
     * @return stdClass
     */
    public function get_records($params = NULL): stdClass  {
        global $DB;
        $data = new stdClass();
        $return = array();

        //SET FILTERS SQL
        $where = "";
        if (array_key_exists('uuid', $params)) {
            if ($where != "") $where .= " AND ";
            $where .= " (oda.uuid = :uuid)";
        }
        if (array_key_exists('user_id', $params)) {
            if ($where != "") $where .= " AND ";
            $where .= " (odu.user_id = :user_id)";
        }


        //SET SQL
        $sql = "SELECT oda.id, oda.uuid, oda.date_start, oda.date_end, oda.altdate_start, oda.altdate_end, oda.destination, 
                oda.activity_type, oda.transport, oda.goals, oda.notes
                FROM {local_odysseus_activities} oda 
                LEFT JOIN {local_odysseus_user} odu ON oda.id = odu.activity_id ";
        $sql .= ($where == "" ? "" : "WHERE" . $where );
        $sql .= " GROUP BY oda.id";
        $sql .= " ORDER BY oda.date_start";

        //LOAD ACTIVITIES FROM DB
        $rs = $DB->get_records_sql($sql, $params);
        foreach ($rs as $record) {
            //LOAD TEACHERS
            $sqlu = "SELECT u.id, odu.user_id, odu.admin, odu.teacher, odu.editor,
                    u.firstname, u.lastname
                    FROM {local_odysseus_user} odu 
                    LEFT JOIN {user} u ON odu.user_id = u.id
                    WHERE (odu.activity_id = :id) 
                    ORDER BY odu.teacher DESC, odu.admin DESC, u.lastname, u.firstname";

            $rsu = $DB->get_records_sql($sqlu, array('id' => $record->id));
            $users = array();
            foreach ($rsu as $user) {
                $users[] = array(
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'admin' => ($user->admin == 1),
                    'teacher' => ($user->teacher == 1),
                    'editor' => ($user->editor == 1)
                );
            }

            $return[] = array(
                'id' => $record->id,
                'uuid' => $record->uuid,
                'date_start' => date("d.m.Y", $record->date_start),
                'date_end' => ($record->date_start == $record->date_end ? null : date("d.m.Y", $record->date_end)),
                'altdate_start' => ($record->altdate_start == 0 ? get_string('no_date', 'local_odysseus') : date("d.m.Y", $record->altdate_start)),
                'date_end' => ($record->altdate_start == $record->altdate_end ? null : date("d.m.Y", $record->altdate_end)),
                'destination' => $record->destination,
                'activity_type' => $record->activity_type,
                'activity_type_text' => $record->activity_type,
                'transport' => $record->transport,
                'transport_text' => $record->transport,
                'goals' => $record->goals,
                'notes' => $record->notes,
                'users' => $users
            );
        }
        $data->recordcount = count($return);
        $data->records = $return;
        return $data;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): stdClass {
        $data = new stdClass();
        $data = $this->get_records($this->params);
        return $data;
    }
}