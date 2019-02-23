package codesum.lm.topicsum;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by amits on 30/10/15.
 */
public class TopicModel {
    private Repository repository;
    private List<Term> terms;
    private List<SalientFile> files;

    public TopicModel() {
        this.terms = new ArrayList<Term>();
        this.files = new ArrayList<SalientFile>();
    }

    public List<SalientFile> getFiles() {
        return files;
    }

    public void setFiles(List<SalientFile> files) {
        this.files = files;
    }

    public List<Term> getTerms() {
        return terms;
    }

    public void setTerms(List<Term> terms) {
        this.terms = terms;
    }

    public Repository getRepository() {
        return repository;
    }

    public void setRepository(Repository repository) {
        this.repository = repository;
    }

    @Override
    public String toString() {
        return "TopicModel[repository=" + repository  + ", terms=" + terms + ", files=" + files + "]";
    }
}
