<?php
// Standard GPL and phpdocs

namespace local_odysseus\output;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    /**
     * Defer to template.
     *
     * @param excursion $excursion
     *
     * @return string html for the page
     */
    public function render_activity_info($activity): string {
        $data = $activity->export_for_template($this);
        if($data->recordcount == 0) return "ERROR";
        return parent::render_from_template('local_odysseus/activity_info', $data->records[0]);
    }

    public function render_activity_list($activity): string {
        $data = $activity->export_for_template($this);
        if($data->recordcount == 0) return get_string('noactivities', 'local_odysseus');
        return parent::render_from_template('local_odysseus/activity_list', $data);
    }
}