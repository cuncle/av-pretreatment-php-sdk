<?php
/**
 * 视频预处理任务：简便对AvPretreatment类的操作
 */
namespace Sugar;

class Tasks {
    /**
     * \Sugar\AvPretreatment @var AvPretreatment
     */
    protected $sugar;
    /**
     * @var array: 待处理的任务
     */
    protected $tasks;
    /**
     * @var string: 空间名
     */
    protected $bucketName;
    /**
     * @var string : 回调通知URL
     */
    protected $notifyUrl;
    /**
     * @var string : 待处理的文件地址
     */
    protected $source;

    /**
     * @var array: 任务ID
     */
    protected $taskIds;
    public  $test;

    public function __construct($bucketName, $notifyUrl, $avPretreatment)
    {
        $this->bucketName = $bucketName;
        $this->notifyUrl = $notifyUrl;
        if($avPretreatment instanceof AvPretreatment) {
            $this->sugar = $avPretreatment;
        } else {
            throw new \Exception('需要一个 AvPretreatment 实例');
        }

        $this->tasks = array();
    }

    /**
     * 添加单个任务
     * @param array $task
     */
    public function addTask($task)
    {
        $this->tasks[] = $task;
    }

    /**
     * 添加一组任务
     * @param array $tasks
     */
    public function addTasks($tasks)
    {
        $this->tasks = array_merge($this->tasks, $tasks);
    }

    /**
     * 清空任务
     */
    public function resetTasks()
    {
        $this->tasks = array();
        $this->taskIds = array();
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function setBucketName($name)
    {
        $this->bucketName = $name;
    }

    public function setNotifyUrl($url)
    {
        $this->notifyUrl = $url;
    }

    /**
     * 执行视频预处理任务
     */
    public function run()
    {
        $data = array(
            'bucket_name' => $this->bucketName,
            'source'      => $this->source,
            'notify_url'  => $this->notifyUrl,
            'tasks'       => $this->tasks,
        );
        $this->taskIds = $this->sugar->request($data);
        return $this->taskIds;
    }

    /**
     * 获取接口返回的任务IDS
     * @return array
     */
    public function getTaskIds()
    {
        return $this->sugar->getTaskIds();
    }


    /**
     * 获取任务状态
     * @return array
     * <code>
     * array(
         'tasks' => array(
     *     'ebc6b85f55b547e18a07cccd867fb961' => '100'
     *   ),
     *   'count' => 1
     * )
     * </code>
     */
    public function getTasksStatus()
    {
        return $this->sugar->getTasksStatus($this->taskIds, $this->bucketName);
    }
}