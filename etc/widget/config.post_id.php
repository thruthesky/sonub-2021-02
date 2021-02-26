<div class="mb-1">
    <div class="d-flex justify-content-between">
        <label class="form-label fs-xs mb-0">글 번호</label>
        <div class="fs-xs pointer" onclick="alert('글 번호(제목이 아님)를 입력해 주세요.')">도움말(?)</div>
    </div>
    <input class="form-control form-control-sm" type="text" name="post_ID" value="<?=$dwo['post_ID']??''?>">
    <div class="form-text fs-xs">
        배너를 클릭하면, 글 읽기 페이지가 열립니다.
    </div>
</div>