public $project;
public $user;

public function __construct($project, $user)
{
    $this->project = $project;
    $this->user = $user;
}

public function build()
{
    return $this->subject('New Project Submitted')
        ->view('emails.project_submitted_to_admin')
        ->with([
            'user' => $this->user,
            'project' => $this->project,
        ]);
}
