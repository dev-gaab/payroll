import React, { Component } from 'react';

export default class Alert extends Component {
    constructor(props) {
        super(props);

    }

    render() {

        if (this.props.isSuccess) {
            return (
                <div className="toast bg-success text-light float-right" role="alert" aria-live="assertive" aria-atomic="true">
                    <div className="toast-header">
                        <strong className="mr-auto">Exito!</strong>
                        <button type="button" className="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div className="toast-body">
                        {this.props.message}
                    </div>
                </div>
            )
        }

        return (
            <div className="toast bg-danger text-light float-right" role="alert" aria-live="assertive" aria-atomic="true">
                <div className="toast-header">
                    <strong className="mr-auto">Error</strong>
                    <button type="button" className="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div className="toast-body">
                    {this.props.message}
                </div>
            </div>
        )
    }
}